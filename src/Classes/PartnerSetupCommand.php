<?php

namespace Dorcas\ModulesDashboard\Classes;

use Illuminate\Console\Command;
use Dorcas\ModulesDashboard\Http\Controllers\ModulesDashboardController;

class PartnerSetupCommand extends Command
{
    protected $signature = 'dorcas:setup-partner {--wallet-preset=}';
    protected $description = 'Command to Setup Partner details from the command line';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        
        // access options
        $walletPreset = $this->option('wallet-preset') ?? false;
        
        $this->info('DORCAS PARTNER SETUP');


        $this->info('Setting up eCommerce Partner Wallets...');
        $wallets = (new ModulesDashboardController())->createPartnerECommerceWallets($walletPreset);
        $setup_wallets = $wallets->getData()->data;
        $walletRefs = '';
        foreach ($setup_wallets as $key => $value) {
            $walletRefs .= $key . ':' . $value . ', ';
        }
        $walletRefs = rtrim($walletRefs, ', ');
        $this->info('Wallet References: ' . $walletRefs);

    }

    
}