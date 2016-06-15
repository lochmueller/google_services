<?php
/**
 * Document controller
 *
 * @author Tim Lochmüller
 */

namespace FRUIT\GoogleServices\Controller;

use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * Document controller
 */
class DocumentController extends AbstractController
{

    /**
     * File repository
     *
     * @var \TYPO3\CMS\Core\Resource\FileRepository
     * @inject
     */
    protected $fileRepository;

    /**
     * Base view
     */
    public function indexAction()
    {
        if (!$this->settings['file']) {
            throw new \Exception('You have to select a valid FAL reference file', 12372183723);
        }

        $images = $this->fileRepository->findByRelation(
            'tt_content',
            'pdf',
            $this->configurationManager->getContentObject()->data['uid']
        );
        if (!sizeof($images)) {
            throw new \Exception('You have to select a valid FAL reference file', 12372183723);
        }
        /** @var FileReference $image */
        $image = current($images);

        $width = MathUtility::canBeInterpretedAsInteger($this->settings['width']) ? $this->settings['width'] . 'px' : $this->settings['width'];
        $height = MathUtility::canBeInterpretedAsInteger($this->settings['height']) ? $this->settings['height'] . 'px' : $this->settings['height'];
        $fileUrl = GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST') . '/' . $image->getPublicUrl();

        $this->view->assignMultiple(array(
            'fileUrl'  => urlencode($fileUrl),
            'language' => $GLOBALS['TSFE']->config['config']['language'],
            'width'    => $width,
            'height'   => $height,
        ));

    }
}
