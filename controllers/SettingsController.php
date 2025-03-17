<?php
class SettingsController {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    /**
     * Get all available modules
     * 
     * @return array Array of modules
     */
    public function getAllModules() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM modules ORDER BY name");
            $stmt->execute();
            $result = $stmt->get_result();
            
            $modules = [];
            while ($row = $result->fetch_assoc()) {
                $modules[] = $row;
            }
            
            return $modules;
        } catch (Exception $e) {
            error_log("Error fetching modules: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get all roles
     * 
     * @return array Array of user roles
     */
    public function getAllRoles() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM roles ORDER BY name");
            $stmt->execute();
            $result = $stmt->get_result();
            
            $roles = [];
            while ($row = $result->fetch_assoc()) {
                $roles[] = $row;
            }
            
            return $roles;
        } catch (Exception $e) {
            error_log("Error fetching roles: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get all menu items with hierarchical structure
     * 
     * @return array Array of menu items
     */
    public function getAllMenuItems() {
        try {
            // First get all menu items
            $stmt = $this->conn->prepare("
                SELECT m.*, mo.name as module_name 
                FROM menu_items m
                LEFT JOIN modules mo ON m.module_id = mo.id
                ORDER BY m.sort_order, m.name
            ");
            $stmt->execute();
            $result = $stmt->get_result();
            
            $menuItems = [];
            $menuMap = [];
            
            // First pass: collect all items
            while ($row = $result->fetch_assoc()) {
                $menuMap[$row['id']] = $row;
                $menuMap[$row['id']]['children'] = [];
                
                if ($row['parent_id'] === null) {
                    $menuItems[$row['id']] = &$menuMap[$row['id']];
                }
            }
            
            // Second pass: build hierarchy
            foreach ($menuMap as $id => $item) {
                if ($item['parent_id'] !== null && isset($menuMap[$item['parent_id']])) {
                    $menuMap[$item['parent_id']]['children'][$id] = $item;
                }
            }
            
            return $menuItems;
        } catch (Exception $e) {
            error_log("Error fetching menu items: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get flat list of all menu items
     * 
     * @return array Array of menu items
     */
    public function getFlatMenuItems() {
        try {
            $stmt = $this->conn->prepare("
                SELECT m.*, mo.name as module_name, p.name as parent_name
                FROM menu_items m
                LEFT JOIN modules mo ON m.module_id = mo.id
                LEFT JOIN menu_items p ON m.parent_id = p.id
                ORDER BY m.sort_order, m.name
            ");
            $stmt->execute();
            $result = $stmt->get_result();
            
            $menuItems = [];
            while ($row = $result->fetch_assoc()) {
                $menuItems[] = $row;
            }
            
            return $menuItems;
        } catch (Exception $e) {
            error_log("Error fetching menu items: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get module permissions for a specific role
     * 
     * @param int $roleId Role ID
     * @return array Array of module permissions
     */
    public function getModulePermissionsByRole($roleId) {
        try {
            $stmt = $this->conn->prepare("
                SELECT m.id, m.name, m.code, m.is_core, mp.is_enabled
                FROM modules m
                LEFT JOIN module_permissions mp ON m.id = mp.module_id AND mp.role_id = ?
                ORDER BY m.name
            ");
            $stmt->bind_param('i', $roleId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $permissions = [];
            while ($row = $result->fetch_assoc()) {
                $permissions[] = $row;
            }
            
            return $permissions;
        } catch (Exception $e) {
            error_log("Error fetching module permissions: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get menu permissions for a specific role
     * 
     * @param int $roleId Role ID
     * @return array Array of menu permissions
     */
    public function getMenuPermissionsByRole($roleId) {
        try {
            $stmt = $this->conn->prepare("
                SELECT mi.id, mi.name, mi.url, mi.parent_id, mi.is_module, mi.module_id, 
                       mp.is_visible, p.name as parent_name
                FROM menu_items mi
                LEFT JOIN menu_permissions mp ON mi.id = mp.menu_id AND mp.role_id = ?
                LEFT JOIN menu_items p ON mi.parent_id = p.id
                ORDER BY mi.sort_order, mi.name
            ");
            $stmt->bind_param('i', $roleId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $permissions = [];
            while ($row = $result->fetch_assoc()) {
                $permissions[] = $row;
            }
            
            return $permissions;
        } catch (Exception $e) {
            error_log("Error fetching menu permissions: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Update module permissions for a specific role
     * 
     * @param int $roleId Role ID
     * @param array $modulePermissions Array of module permissions [module_id => is_enabled]
     * @return bool Success status
     */
    public function updateModulePermissions($roleId, $modulePermissions) {
        try {
            $this->conn->begin_transaction();
            
            // Delete existing permissions for this role
            $stmt = $this->conn->prepare("DELETE FROM module_permissions WHERE role_id = ?");
            $stmt->bind_param('i', $roleId);
            $stmt->execute();
            
            // Insert new permissions
            $stmt = $this->conn->prepare("INSERT INTO module_permissions (module_id, role_id, is_enabled) VALUES (?, ?, ?)");
            
            foreach ($modulePermissions as $moduleId => $isEnabled) {
                $enabled = ($isEnabled) ? 1 : 0;
                $stmt->bind_param('iii', $moduleId, $roleId, $enabled);
                $stmt->execute();
            }
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Error updating module permissions: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update menu permissions for a specific role
     * 
     * @param int $roleId Role ID
     * @param array $menuPermissions Array of menu permissions [menu_id => is_visible]
     * @return bool Success status
     */
    public function updateMenuPermissions($roleId, $menuPermissions) {
        try {
            $this->conn->begin_transaction();
            
            // Delete existing permissions for this role
            $stmt = $this->conn->prepare("DELETE FROM menu_permissions WHERE role_id = ?");
            $stmt->bind_param('i', $roleId);
            $stmt->execute();
            
            // Insert new permissions
            $stmt = $this->conn->prepare("INSERT INTO menu_permissions (menu_id, role_id, is_visible) VALUES (?, ?, ?)");
            
            foreach ($menuPermissions as $menuId => $isVisible) {
                $visible = ($isVisible) ? 1 : 0;
                $stmt->bind_param('iii', $menuId, $roleId, $visible);
                $stmt->execute();
            }
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Error updating menu permissions: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if a module is enabled for a user
     * 
     * @param int $moduleId Module ID
     * @param int $roleId Role ID
     * @return bool Whether module is enabled
     */
    public function isModuleEnabled($moduleId, $roleId) {
        try {
            // Super admin always has access to all modules
            $stmt = $this->conn->prepare("SELECT is_admin FROM roles WHERE id = ?");
            $stmt->bind_param('i', $roleId);
            $stmt->execute();
            $result = $stmt->get_result();
            $role = $result->fetch_assoc();
            
            if ($role['is_admin']) {
                return true;
            }
            
            // Check module permissions
            $stmt = $this->conn->prepare("
                SELECT is_enabled 
                FROM module_permissions 
                WHERE module_id = ? AND role_id = ?
            ");
            $stmt->bind_param('ii', $moduleId, $roleId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                return false;
            }
            
            $permission = $result->fetch_assoc();
            return (bool) $permission['is_enabled'];
        } catch (Exception $e) {
            error_log("Error checking module permission: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if a menu item is visible for a user
     * 
     * @param int $menuId Menu ID
     * @param int $roleId Role ID
     * @return bool Whether menu item is visible
     */
    public function isMenuItemVisible($menuId, $roleId) {
        try {
            // Super admin always has access to all menu items
            $stmt = $this->conn->prepare("SELECT is_admin FROM roles WHERE id = ?");
            $stmt->bind_param('i', $roleId);
            $stmt->execute();
            $result = $stmt->get_result();
            $role = $result->fetch_assoc();
            
            if ($role['is_admin']) {
                return true;
            }
            
            // Check menu permissions
            $stmt = $this->conn->prepare("
                SELECT is_visible
                FROM menu_permissions 
                WHERE menu_id = ? AND role_id = ?
            ");
            $stmt->bind_param('ii', $menuId, $roleId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                return false;
            }
            
            $permission = $result->fetch_assoc();
            return (bool) $permission['is_visible'];
        } catch (Exception $e) {
            error_log("Error checking menu permission: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get navigation menu for a specific role
     * 
     * @param int $roleId Role ID
     * @return array Hierarchical menu structure
     */
    public function getNavigationMenu($roleId) {
        try {
            // Super admin always sees all menu items
            $isSuperAdmin = false;
            
            $stmt = $this->conn->prepare("SELECT is_admin FROM roles WHERE id = ?");
            $stmt->bind_param('i', $roleId);
            $stmt->execute();
            $result = $stmt->get_result();
            $role = $result->fetch_assoc();
            
            if ($role && $role['is_admin']) {
                $isSuperAdmin = true;
            }
            
            // Get all menu items
            $stmt = $this->conn->prepare("
                SELECT m.*, mp.is_visible, mo.id as module_id
                FROM menu_items m
                LEFT JOIN menu_permissions mp ON m.id = mp.menu_id AND mp.role_id = ?
                LEFT JOIN modules mo ON m.module_id = mo.id
                ORDER BY m.sort_order, m.name
            ");
            $stmt->bind_param('i', $roleId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $menuItems = [];
            $menuMap = [];
            
            // First pass: collect all visible items
            while ($row = $result->fetch_assoc()) {
                // Skip items that are not visible for this role
                if (!$isSuperAdmin && (!$row['is_visible'] && $row['is_visible'] !== null)) {
                    continue;
                }
                
                // If module is linked and not enabled, skip
                if ($row['module_id'] && !$isSuperAdmin) {
                    $moduleEnabled = $this->isModuleEnabled($row['module_id'], $roleId);
                    if (!$moduleEnabled) {
                        continue;
                    }
                }
                
                $menuMap[$row['id']] = $row;
                $menuMap[$row['id']]['children'] = [];
                
                if ($row['parent_id'] === null) {
                    $menuItems[$row['id']] = &$menuMap[$row['id']];
                }
            }
            
            // Second pass: build hierarchy
            foreach ($menuMap as $id => $item) {
                if ($item['parent_id'] !== null && isset($menuMap[$item['parent_id']])) {
                    $menuMap[$item['parent_id']]['children'][$id] = $item;
                }
            }
            
            // Third pass: remove empty parent menu items
            foreach ($menuItems as $id => $item) {
                if (count($item['children']) === 0 && $item['url'] === '#') {
                    unset($menuItems[$id]);
                }
            }
            
            return $menuItems;
        } catch (Exception $e) {
            error_log("Error building navigation menu: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Add a new menu item
     * 
     * @param array $data Menu item data
     * @return int|bool New menu ID or false on failure
     */
    public function addMenuItem($data) {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO menu_items (name, url, icon, parent_id, sort_order, is_module, module_id)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            $parentId = !empty($data['parent_id']) ? $data['parent_id'] : null;
            $isModule = isset($data['is_module']) ? $data['is_module'] : false;
            $moduleId = !empty($data['module_id']) ? $data['module_id'] : null;
            $sortOrder = !empty($data['sort_order']) ? $data['sort_order'] : 0;
            
            $stmt->bind_param('sssiiii', 
                $data['name'], 
                $data['url'], 
                $data['icon'],
                $parentId,
                $sortOrder,
                $isModule,
                $moduleId
            );
            
            if (!$stmt->execute()) {
                throw new Exception('Failed to add menu item');
            }
            
            return $this->conn->insert_id;
        } catch (Exception $e) {
            error_log("Error adding menu item: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update an existing menu item
     * 
     * @param int $id Menu item ID
     * @param array $data Updated menu item data
     * @return bool Success status
     */
    public function updateMenuItem($id, $data) {
        try {
            $stmt = $this->conn->prepare("
                UPDATE menu_items
                SET name = ?, url = ?, icon = ?, parent_id = ?, sort_order = ?, is_module = ?, module_id = ?
                WHERE id = ?
            ");
            
            $parentId = !empty($data['parent_id']) ? $data['parent_id'] : null;
            $isModule = isset($data['is_module']) ? $data['is_module'] : false;
            $moduleId = !empty($data['module_id']) ? $data['module_id'] : null;
            $sortOrder = !empty($data['sort_order']) ? $data['sort_order'] : 0;
            
            $stmt->bind_param('sssiiii', 
                $data['name'], 
                $data['url'], 
                $data['icon'],
                $parentId,
                $sortOrder,
                $isModule,
                $moduleId,
                $id
            );
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error updating menu item: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete a menu item
     * 
     * @param int $id Menu item ID
     * @return bool Success status
     */
    public function deleteMenuItem($id) {
        try {
            // First delete any child menu items
            $stmt = $this->conn->prepare("DELETE FROM menu_items WHERE parent_id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            
            // Then delete the menu item itself
            $stmt = $this->conn->prepare("DELETE FROM menu_items WHERE id = ?");
            $stmt->bind_param('i', $id);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error deleting menu item: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Add a new module
     * 
     * @param array $data Module data
     * @return int|bool New module ID or false on failure
     */
    public function addModule($data) {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO modules (name, code, description, is_core)
                VALUES (?, ?, ?, ?)
            ");
            
            $isCore = isset($data['is_core']) ? $data['is_core'] : false;
            
            $stmt->bind_param('sssi', 
                $data['name'], 
                $data['code'], 
                $data['description'],
                $isCore
            );
            
            if (!$stmt->execute()) {
                throw new Exception('Failed to add module');
            }
            
            return $this->conn->insert_id;
        } catch (Exception $e) {
            error_log("Error adding module: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update an existing module
     * 
     * @param int $id Module ID
     * @param array $data Updated module data
     * @return bool Success status
     */
    public function updateModule($id, $data) {
        try {
            $stmt = $this->conn->prepare("
                UPDATE modules
                SET name = ?, code = ?, description = ?, is_core = ?
                WHERE id = ?
            ");
            
            $isCore = isset($data['is_core']) ? $data['is_core'] : false;
            
            $stmt->bind_param('sssii', 
                $data['name'], 
                $data['code'], 
                $data['description'],
                $isCore,
                $id
            );
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error updating module: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete a module
     * 
     * @param int $id Module ID
     * @return bool Success status
     */
    public function deleteModule($id) {
        try {
            // First check if module is used by any menu items
            $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM menu_items WHERE module_id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if ($row['count'] > 0) {
                throw new Exception('Cannot delete module that is associated with menu items');
            }
            
            // Delete the module
            $stmt = $this->conn->prepare("DELETE FROM modules WHERE id = ?");
            $stmt->bind_param('i', $id);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error deleting module: " . $e->getMessage());
            return false;
        }
    }
} 