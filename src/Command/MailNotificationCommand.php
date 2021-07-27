<?php

namespace App\Command;

use App\Repository\MeminiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailNotificationCommand extends Command
{
    protected static $defaultName = 'app:mail-notification';

    private $meminiRepository;
    private $mailer;

    public function __construct(EntityManagerInterface $em, MeminiRepository $meminiRepository, MailerInterface $mailer)
    {
        parent::__construct();
        $this->em = $em;
        $this->meminiRepository = $meminiRepository;
        $this->mailer = $mailer;
    }

    protected function configure()
    {
        $this
            ->setDescription('Cherche les Meminis à envoyer et envoie une notification par mail pour informer les utilisateurs qu\'un Memini est arrivé')
            // ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            // ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        // $arg1 = $input->getArgument('arg1');

        // if ($arg1) {
        //     $io->note(sprintf('You passed an argument: %s', $arg1));
        // }

        // if ($input->getOption('option1')) {
            // ...
        // }

        // Récupération des meminis à envoyer
        $meminisToSend = $this->meminiRepository->findAllMeminisToSend();

        // boucle pour créer et envoyer chaque email
        foreach($meminisToSend as $memini) {

            // Changement du status de la propriété isSent du Memini
            $memini->setIsSent(1);
            $this->em->flush();


            // Création d'email
            $meminiSentAt = date_format($memini->getCreatedAt(), "d/m/Y");
            $email = (new TemplatedEmail())
            ->from('meminikrakahouette@gmail.com')
            ->to($memini->getUser()->getEmail())
            ->subject('Memini Memini')
            ->htmlTemplate('emails/sending.html.twig')
            ->context([
                'memini' => $memini,
                'date' => $meminiSentAt,
            ])
            ;

            // dd($email);

            // Envoi de d'email
            $this->mailer->send($email);
            }
        

        $io->success('Les notification par mail on été envoyées');

        return Command::SUCCESS;
    }
}
