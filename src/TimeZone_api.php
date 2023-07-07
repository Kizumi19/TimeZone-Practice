<?php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Application;

class TimeZoneCommand extends Command
{
    protected static $defaultName = 'time-zone';

    protected function configure()
    {
        $this->setDescription('Provides the time for a given time zone');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            "Welcome! Please enter the time zone for which you want to know the current time. For example: Cuba" . PHP_EOL . 
            "Otherwise, if you prefer to check the principal time zones, choose the options you prefer!".PHP_EOL,
            ['A' => 'Amsterdam', 'B' => 'Madrid', 'C' => 'Tokyo', 'D' => 'Sydney'],
            'A'
        );
        $question->setErrorMessage('Option %s is invalid.');
        $timezone = $helper->ask($input, $output, $question);

        switch ($timezone) {
            case "A":
                $url = "https://timeapi.io/api/Time/current/zone?timeZone=Europe/Amsterdam";
                break;
            case "B":
                $url = "https://timeapi.io/api/Time/current/zone?timeZone=Europe/Madrid";
                break;
            case "C":
                $url = "https://timeapi.io/api/Time/current/zone?timeZone=Asia/Tokyo";
                break;
            case "D":
                $url = "https://timeapi.io/api/Time/current/zone?timeZone=Australia/Sydney";
                break;
            default:
                $url = "https://timeapi.io/api/Time/current/zone?timeZone=". $timezone;
        }

        $client = HttpClient::create();
        $response = $client->request('GET', $url);

        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getContent());
            $date = date('d-m-Y', strtotime($data->date));
            $output->writeln("Date: ". $date);
            $output->writeln("Time: ". $data->time);
        } else {
            $output->writeln('Something went wrong');
            $output->writeln("--> TIP! You can search in https://en.wikipedia.org/wiki/List_of_tz_database_time_zones to be more specific which country you want to check its timezone");
        }

        $output->writeln('Thanks for using this command!');
        return Command::SUCCESS;
    }
}

$application = new Application();
$application->add(new TimeZoneCommand());
$application->run();
