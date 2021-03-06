<?php

/**
 * @version    1.0.1
 * @package    Com_Xws_linked_keywords
 * @author     Tony Partridge <tony@xws.im>
 * @copyright  2017 Tony Partridge - Xtech Web Services Ltd
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JLoader::register('Xws_linked_keywordsHelper', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_xws_linked_keywords' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'xws_linked_keywords.php');

/**
 * Class Xws_linked_keywordsFrontendHelper
 *
 * @since  1.6
 */
class Xws_linked_keywordsHelpersXws_linked_keywords
{
	/**
	* Get menuitem name using group ID
	* @param integer $menuitem_id Menuitem ID
	* @return mixed menuitem name if the menuitem was found, null otherwise
	*/
	public static function getMenuItemNameByMenuItemId($menuitem_id) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select('title')
			->from('#__menu')
			->where('id = ' . intval($menuitem_id));

		$db->setQuery($query);
		return $db->loadResult();
	}
	/**
	 * Get an instance of the named model
	 *
	 * @param   string  $name  Model name
	 *
	 * @return null|object
	 */
	public static function getModel($name)
	{
		$model = null;

		// If the file exists, let's
		if (file_exists(JPATH_SITE . '/components/com_xws_linked_keywords/models/' . strtolower($name) . '.php'))
		{
			require_once JPATH_SITE . '/components/com_xws_linked_keywords/models/' . strtolower($name) . '.php';
			$model = JModelLegacy::getInstance($name, 'Xws_linked_keywordsModel');
		}

		return $model;
	}

	/**
	 * Gets the files attached to an item
	 *
	 * @param   int     $pk     The item's id
	 *
	 * @param   string  $table  The table's name
	 *
	 * @param   string  $field  The field's name
	 *
	 * @return  array  The files
	 */
	public static function getFiles($pk, $table, $field)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select($field)
			->from($table)
			->where('id = ' . (int) $pk);

		$db->setQuery($query);

		return explode(',', $db->loadResult());
	}

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
    public static function canUserEdit($item)
    {
        $permission = false;
        $user       = JFactory::getUser();

        if ($user->authorise('core.edit', 'com_xws_linked_keywords'))
        {
            $permission = true;
        }
        else
        {
            if (isset($item->created_by))
            {
                if ($user->authorise('core.edit.own', 'com_xws_linked_keywords') && $item->created_by == $user->id)
                {
                    $permission = true;
                }
            }
            else
            {
                $permission = true;
            }
        }

        return $permission;
    }
}
