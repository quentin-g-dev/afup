<?php

namespace AppBundle\Controller\Admin\Site;

use AppBundle\Controller\SiteBaseController;
use Afup\Site\Logger\DbLoggerTrait;
use AppBundle\Site\Form\FeuilleType;
use AppBundle\Site\Model\Repository\FeuilleRepository;
use AppBundle\Site\Model\Feuille;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Exception;

class AddFeuilleAction extends SiteBaseController
{
    use DbLoggerTrait;

    /** @var FlashBagInterface */
    private $flashBag;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var Environment */
    private $twig;

    /** @var FeuilleRepository */
    private $feuilleRepository;

    /** @var string */
    private $storageDir;

    public function __construct(
        FeuilleRepository $feuilleRepository,
        Environment $twig,
        UrlGeneratorInterface $urlGenerator,
        FlashBagInterface $flashBag,
        $storageDir
    ) {
        $this->feuilleRepository =  $feuilleRepository;
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
        $this->flashBag = $flashBag;
        $this->storageDir = $storageDir;
    }

    public function __invoke(Request $request)
    {
        $feuille = new Feuille();
        $form = $this->createForm(FeuilleType::class, $feuille);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = hash('sha1', $originalFilename);
                $newFilename = $safeFilename . '.' . $file->guessExtension();
                try {
                    $file->move($this->storageDir, $newFilename);
                    $feuille->setIcone($newFilename);
                } catch (FileException $e) {
                    $this->flashBag->add('error', 'Une erreur est survenue lors du traitement de l\'icône');
                }
            }
            try {
                $this->feuilleRepository->save($feuille);
                $this->log('Ajout de la feuille ' . $feuille->getNom());
                $this->flashBag->add('notice', 'La feuille '. $feuille->getNom() .' a été ajoutée');
                return new RedirectResponse($this->urlGenerator->generate('admin_site_feuilles_list', ['filter' => $feuille->getNom()]));
            } catch (Exception $e) {
                $this->flashBag->add('error', 'Une erreur est survenue  lors de l\'ajout de la feuille');
            }
        }

        return new Response($this->twig->render('admin/site/feuille_form.html.twig', [
            'form' => $form->createView(),
            'formTitle' => 'Ajouter une feuille',
            'subtitle' => false,
            'image' => false,
        ]));
    }
}
