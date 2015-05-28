<?php
/**
 * Document controller
 *
 * @category   Extension
 * @package    GoogleServices
 * @author     Tim Lochmüller <tim.lochmueller@hdnet.de>
 */

# namespace GS\GoogleServices\Controller;

/**
 * Document controller
 *
 * @package    GoogleServices
 * @subpackage Controller
 * @author     Tim Lochmüller <tim@fruit-lab.de>
 */
class Tx_GoogleServices_Controller_DocumentController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

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
	public function indexAction() {
		if (!$this->settings['file']) {
			throw new \Exception('You have to select a valid FAL reference file', 12372183723);
		}

		$images = $this->fileRepository->findByRelation('tt_content', 'pdf', $this->configurationManager->getContentObject()->data['uid']);
		if (!sizeof($images)) {
			throw new \Exception('You have to select a valid FAL reference file', 12372183723);
		}
		/** @var \TYPO3\CMS\Core\Resource\FileReference $image */
		$image = current($images);

		$width = \TYPO3\CMS\Core\Utility\MathUtility::canBeInterpretedAsInteger($this->settings['width']) ? $this->settings['width'] . 'px' : $this->settings['width'];
		$height = \TYPO3\CMS\Core\Utility\MathUtility::canBeInterpretedAsInteger($this->settings['height']) ? $this->settings['height'] . 'px' : $this->settings['height'];
		$fileUrl = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST') . '/' . $image->getPublicUrl();

		$this->view->assignMultiple(array(
			'fileUrl'  => urlencode($fileUrl),
			'language' => $GLOBALS['TSFE']->config['config']['language'],
			'width'    => $width,
			'height'   => $height,
		));

	}

}