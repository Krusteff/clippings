<?php

namespace Infrastructure;

/**
 * Class Renderer
 *
 * @author Martin Krastev <martin.krastev@devision.bg>
 */
class Renderer
{
    /**
     * @param string $view
     * @param array $data
     *
     * @return string
     */
    public function render(string $view, array $data = ['test' => 1]): string
    {
        extract($data);
        ob_start();

        require_once __DIR__ . '/../../templates/' . $view;

        return ob_get_clean() ?? '';
    }
}
