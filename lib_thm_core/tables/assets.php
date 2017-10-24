<?php
/**
 * @category    Joomla library
 * @package     THM_Core
 * @subpackage  lib_thm_core.site
 * @name        THM_CoreTableAssets
 * @author      James Antrim, <james.antrim@mni.thm.de>
 * @copyright   2015 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.mni.thm.de
 */
defined('_JEXEC') or die;
jimport('joomla.database.table');

/**
 * Class representing the assets table.
 *
 * @category    Joomla.Library
 * @package     THM_Core
 * @subpackage  com_thm_organizer.admin.tables
 */
class THM_CoreTableAssets extends JTable
{
    public $asset_id;

    /**
     * Method to store a row in the database from the JTable instance properties. Completely overwrites the method in
     * JTable because they use the subclass specific update nulls setting for assets which is just stupid.
     *
     * @param   boolean $updateNulls True to update fields even if they are null.
     *
     * @return  boolean  True on success.
     */
    public function store($updateNulls = true)
    {
        $keys = $this->_tbl_keys;

        // Implement JObservableInterface: Pre-processing by observers
        $this->_observers->update('onBeforeStore', array($updateNulls, $keys));

        $currentAssetId = 0;

        if ( ! empty($this->asset_id)) {
            $currentAssetId = $this->asset_id;
        }

        // The asset id field is managed privately by this class.
        if ($this->_trackAssets) {
            unset($this->asset_id);
        }

        // If a primary key exists update the object, otherwise insert it.
        if ($this->hasPrimaryKey()) {
            $result = $this->_db->updateObject($this->_tbl, $this, $this->_tbl_keys, $updateNulls);
        } else {
            $result = $this->_db->insertObject($this->_tbl, $this, $this->_tbl_keys[0]);
        }

        // If the table is not set to track assets return true.
        if ($this->_trackAssets) {
            if ($this->_locked) {
                $this->_unlock();
            }

            /*
             * Asset Tracking
             */
            $parentId = $this->_getAssetParentId();
            $name = $this->_getAssetName();
            $title = $this->_getAssetTitle();

            $asset = self::getInstance('Asset', 'JTable', array('dbo' => $this->getDbo()));
            $asset->loadByName($name);

            // Re-inject the asset id.
            $this->asset_id = $asset->id;

            // Check for an error.
            $error = $asset->getError();

            if ($error) {
                $this->setError($error);

                return false;
            } else {
                // Specify how a new or moved node asset is inserted into the tree.
                if (empty($this->asset_id) || $asset->parent_id != $parentId) {
                    $asset->setLocation($parentId, 'last-child');
                }

                // Prepare the asset to be stored.
                $asset->parent_id = $parentId;
                $asset->name = $name;
                $asset->title = $title;

                if ($this->_rules instanceof JAccessRules) {
                    $asset->rules = (string)$this->_rules;
                }

                if ( ! $asset->check() || ! $asset->store(false)) {
                    $this->setError($asset->getError());

                    return false;
                } else {
                    // Create an asset_id or heal one that is corrupted.
                    if (empty($this->asset_id) || ($currentAssetId != $this->asset_id && ! empty($this->asset_id))) {
                        // Update the asset_id field in this table.
                        $this->asset_id = (int)$asset->id;

                        $query = $this->_db->getQuery(true)
                            ->update($this->_db->quoteName($this->_tbl))
                            ->set('asset_id = ' . (int)$this->asset_id);
                        $this->appendPrimaryKeys($query);
                        $this->_db->setQuery($query)->execute();
                    }
                }
            }
        }

        // Implement JObservableInterface: Post-processing by observers
        $this->_observers->update('onAfterStore', array(&$result));

        return $result;
    }

    /**
     * Overridden bind function
     *
     * @param   array $array named array
     * @param   mixed $ignore An optional array or space separated list of properties to ignore while binding.
     *
     * @return  mixed  Null if operation was satisfactory, otherwise returns an error string
     */
    public function bind($array, $ignore = '')
    {
        if (isset($array['rules']) && is_array($array['rules'])) {
            self::cleanRules($array['rules']);
            $rules = new JAccessRules($array['rules']);
            $this->setRules($rules);
        }

        return parent::bind($array, $ignore);
    }

    /**
     * Removes inherited groups before Joomla erroneously sets the value to 0. Joomla must have something similar, but I
     * don't have time to look for it.
     *
     * @param   array &$rules the rules from the form
     *
     * @return  void  unsets group indexes with a truly empty value
     */
    private static function cleanRules(&$rules)
    {
        foreach ($rules as $rule => $groups) {
            foreach ($groups as $group => $value) {
                if (empty($value) AND $value !== 0) {
                    unset($rules[$rule][$group]);
                }
            }
        }
    }
}
