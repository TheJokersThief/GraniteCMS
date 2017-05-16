<?php

namespace App\Helpers;

class Hook
{
    private $hooks = [];

    public function addHook($tag, $priority, $function)
    {
        $this->initHook($tag);

        if (array_key_exists($priority, $this->hooks[$tag])) {
            // If the key exists, we don't want to overwrite it so
            // continue until you reach a key that's free
            return $this->addHook($tag, $priority + 1, $function);
        } else {
            $this->hooks[$tag][$priority] = $function;
        }

        return $this->hooks[$tag];
    }

    /**
     * Creates new hook array if one doesn't already exist
     * @param  string $tag     The key for the hook
     */
    public function initHook($tag)
    {
        if (!array_key_exists($tag, $this->hooks)) {
            $this->hooks[$tag] = [];
        }
    }

    public function execute($tag, $params)
    {
        $params['result'] = null;

        foreach ($this->hooks[$tag] as $function) {
            $result = call_user_func_array($function, $params);
            if ($result != null) {
                $params['result'] = $result;
            }
        }

        return $params['result'];
    }
}
