<?php
/**
 * Dev-Toolbox Custom Utility Collection.
 *
 * Extra methods for the eloquent collection.
 *
 *
 * @author      RiDdLeS <riddles@dev-toolbox.com>
 * @version     0.1
 */

class Utility_Collection extends Illuminate\Database\Eloquent\Collection {

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        $newCollection = new Utility_Collection();
        foreach ($this->items as $item) {
            if ($item instanceof Utility_Collection) {
                foreach ($item as $subItem) {
                    $newCollection->put($newCollection->count(), $subItem->$key);
                }
            }
            elseif (is_object($item) && !$item instanceof Utility_Collection && $item->$key instanceof Utility_Collection) {
                foreach ($item->$key as $subItem) {
                    $newCollection->put($newCollection->count(), $subItem);
                }
            }
            else {
                $newCollection->put($newCollection->count(), $item->$key);
            }
        }
        return $newCollection;
    }

    /**
     * Allow a method to be run on the enitre collection.
     *
     * @param string $method
     * @param array $args
     * @return Utility_Collection
     */
    public function __call($method, $args)
    {
        if ($this->count() <= 0) {
            return $this;
        }

        foreach ($this->items as $item) {
            if (!is_object($item)) {
                continue;
            }
            call_user_func_array(array($item, $method), $args);
        }

        return $this;
    }
}