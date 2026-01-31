<?php
namespace SCER\Core;

class Loader {

    public function init() {

        // Admin
        if ( is_admin() ) {
            $this->init_admin();
        }

        // Frontend (future)
        $this->init_frontend();
    }

    private function init_admin() {
        // SCER_Admin constructor will enqueue assets & initialize MetaBox
        new \SCER\Admin\SCER_Admin();
    }

  private function init_frontend() {
    new \SCER\Frontend\Frontend();
}
}
