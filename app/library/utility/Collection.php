<?php

class Utility_Collection extends Illuminate\Support\Collection {

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
}