<?php
namespace SCER\Core;

class Loader {

    public function init() {

        /**
         * -------------------------------------------------
         * Register Expiry Action Handler (Global)
         * -------------------------------------------------
         */
        add_action(
            'scer_do_expiry_action',
            [ '\SCER\Core\Actions', 'run' ]
        );

        /**
         * -------------------------------------------------
         * Initialize Cron (MUST be global)
         * -------------------------------------------------
         */
        new \SCER\Core\Cron();

        /**
         * -------------------------------------------------
         * Admin Initialization
         * -------------------------------------------------
         */
        if ( is_admin() ) {
            $this->init_admin();
        }

        /**
         * -------------------------------------------------
         * Frontend Initialization
         * -------------------------------------------------
         */
        $this->init_frontend();
    }

    /**
     * Admin Specific Features
     */
    private function init_admin() {

        new \SCER\Admin\SCER_Admin();

    }

    /**
     * Frontend Specific Features
     */
    private function init_frontend() {

        new \SCER\Frontend\Frontend();

    }
}
