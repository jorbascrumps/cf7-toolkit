<?php

namespace CF7_Toolkit\Panels;

abstract class Panel {
    protected $name;
    protected $title;

    public function __construct()
    {
        $this->name = 'wpcf7_toolkit_' . strtolower($this->title);

        add_action( 'wpcf7_editor_panels', [ $this, 'register' ] );
        add_action( 'wpcf7_after_save', [ $this, 'save' ] );
    }

    public function register($panels): array
    {
        $panels[$this->name] = [
            'title' => $this->title,
            'callback' => [ $this, 'render' ],
        ];

        return $panels;
    }

    public function render($form): void
    {
        echo <<<RENDER
            <h2>{$this->title}</h2>
        RENDER;
    }

    public function save($form): void
    {
        if ( empty( $_POST ) ) {
            return;
        }

        update_option( "{$this->name}_{$form->id()}", $_POST[$this->name] );
    }
}
