<?php

function recursiveEncryptCollection($collection, $child_attr_name = "children", $fields_to_encrypt = [])
{

    foreach ($collection as $item_key => $item) {
        if (isset($item[$child_attr_name]) || $item[$child_attr_name]->isEmpty()) {
            foreach ($item[$child_attr_name] as $key => $child) {
                foreach ($fields_to_encrypt as $field) {
                    $item[$child_attr_name][$key]["encrypted_" . $field] = encrypt($child[$field]);
                }
            }

            $collection[$item_key] = $item;
        }
    }

    return $collection;
}
