<?php

namespace Dorcas\ModulesDashboard\Classes;

use Illuminate\Console\Command;
use Dorcas\ModulesDashboard\Http\Controllers\ModulesDashboardController;
use Dorcas\ModulesDashboard\Http\Controllers\PartnerSetupController;

class PartnerSetupCommand extends Command
{
    protected $signature = 'dorcas:setup-partner {--wallet-preset=} {--vas-products=}';
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
        $walletPresetOption = $this->option('wallet-preset') ?? null;
        $vasProductsOption = $this->option('vas-products') ?? null;
        
        $this->info('DORCAS PARTNER SETUP');

        if ($walletPresetOption !== null ) {

            $this->info('Setting up eCommerce Partner Wallets...');
            try {
    
                $wallets = (new PartnerSetupController())->createPartnerECommerceWallets($walletPresetOption);
                if ($wallets->getData()->status) {
                    $setup_wallets = $wallets->getData()->data;
                    $walletRefs = '';
                    foreach ($setup_wallets as $key => $value) {
                        $walletRefs .= $key . ':' . $value . ', ';
                    }
                    $walletRefs = rtrim($walletRefs, ', ');
                    $this->info('Wallet References: ' . $walletRefs);
                } else {
                    $this->error('Issue Setting up eCommerce Partner Wallets: ' . $wallets->getData()->message);
                }
    
            } catch (\Exception $e) {
                $this->error('Error Setting up eCommerce Partner Wallets: ' . $e->getMessage());
                //throw new \RuntimeException($e->getMessage());
            }

        }


        if ($vasProductsOption !== null ) {

            $this->info('Setting up Partner VAS Products...');
            try {
    
                $wallets = (new PartnerSetupController())->createPartnerVASProducts($vasProductsOption);
                if ($wallets->getData()->status) {
                    $setup_wallets = $wallets->getData()->data;
                    $walletRefs = '';
                    foreach ($setup_wallets as $key => $value) {
                        $walletRefs .= $key . ':' . $value . ', ';
                    }
                    $walletRefs = rtrim($walletRefs, ', ');
                    $this->info('Wallet References: ' . $walletRefs);
                } else {
                    $this->error('Issue Setting up eCommerce Partner Wallets: ' . $wallets->getData()->message);
                }
    
            } catch (\Exception $e) {
                $this->error('Error Setting up eCommerce Partner Wallets: ' . $e->getMessage());
                //throw new \RuntimeException($e->getMessage());
            }

        }

        

    }

    
}