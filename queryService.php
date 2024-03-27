<?php

// Include Composer's autoloader to load dependencies
require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use League\Csv\Reader;

class ServiceCatalogueCLI extends \Symfony\Component\Console\Command\Command
{
    protected static $defaultName = 'service:query';

    protected function configure()
    {
        $this->setDescription('Query service data based on country code')
            ->addArgument('country_code', InputArgument::REQUIRED, 'Country code to filter services');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Read the CSV file
        $reader = Reader::createFromPath('services.csv', 'r');
        $reader->setHeaderOffset(0);
        $services = $reader->getRecords();

        // Initialize array to store services for the given country
        $servicesForCountry = [];

        // Iterate through each service record
        foreach ($services as $service) {
            if (strtoupper($service['Country']) === strtoupper($input->getArgument('country_code'))) {
                // Store service and center for the given country
                $servicesForCountry[] = [
                    'Service' => $service['Service'],
                    'Centre' => $service['Centre'] // Added 'Center' information
                ];
            }
        }

        // Output services for the given country
        if (!empty($servicesForCountry)) {
            $output->writeln('Services provided by ' . strtoupper($input->getArgument('country_code')) . ':');
            foreach ($servicesForCountry as $serviceInfo) {
                // Output service and center information
                $output->writeln('- ' . $serviceInfo['Service'] . ' @ ' . $serviceInfo['Centre']);
            }
        } else {
            $output->writeln('No services found for ' . strtoupper($input->getArgument('country_code')));
        }

        return 0; // Return 0 indicating successful execution
    }
}

class ServiceSummaryCLI extends \Symfony\Component\Console\Command\Command
{
    protected static $defaultName = 'service:summary';

    protected function configure()
    {
        $this->setDescription('Display summary of services by country');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Read the CSV file
        $reader = Reader::createFromPath('services.csv', 'r');
        $reader->setHeaderOffset(0);
        $services = $reader->getRecords();

        // Initialize array to store number of services by country
        $serviceCountsByCountry = [];

        // Iterate through each service record
        foreach ($services as $service) {
            $countryCode = strtoupper($service['Country']);
            // Increment service count for the country
            if (!isset($serviceCountsByCountry[$countryCode])) {
                $serviceCountsByCountry[$countryCode] = 0;
            }
            $serviceCountsByCountry[$countryCode]++;
        }

        // Output summary of services by country
        $output->writeln('Summary of services by country:');
        foreach ($serviceCountsByCountry as $countryCode => $count) {
            $output->writeln('- ' . $countryCode . ': ' . $count);
        }

        return 0; // Return 0 indicating successful execution
    
    }
}

// Initialize the CLI application
$application = new Application();
// Add commands to the application
$application->add(new ServiceCatalogueCLI());
$application->add(new ServiceSummaryCLI());
// Run the application
$application->run();
