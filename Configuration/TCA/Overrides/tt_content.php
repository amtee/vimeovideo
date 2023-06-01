<?php
use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \Brightside\Vimeovideo\Preview\VimeovideoPreviewRenderer;

defined('TYPO3') || die('Access denied.');

$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['vimeovideo_pi1'] =  'vimeovideo_icon';

ExtensionManagementUtility::addLLrefForTCAdescr(
	'tt_content', 'EXT:vimeovideo/Resources/Private/Language/locallang_db.xlf'
);

// Get extension configuration
$extensionConfiguration = GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
);
$extensionConfiguration = $extensionConfiguration->get('vimeovideo');

// Add to content type dropdown
ExtensionManagementUtility::addTcaSelectItem(
    "tt_content",
    "CType",
    [
        'Vimeo Video',
        'vimeovideo_pi1',
        'vimeovideo_icon'
    ],
    'textmedia',
    'after'
);

$tempColumns = array(
	'tx_vimeovideo_assets' => [
		'exclude' => 1,
		'label' => 'Video',
		'config' => ExtensionManagementUtility::getFileFieldTCAConfig('tx_vimeovideo_assets', [
			'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
			'appearance' => [
				'createNewRelationLinkTitle' => 'Video',
				'showPossibleLocalizationRecords' => true,
			],
			'overrideChildTca' => [
				'types' => [
					\TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
						'showitem' => '
							--palette--;;vimeovideoOverlayPalette,
							--palette--;;filePalette',
					],
				],
			],
		], 'vimeo'),
	],
	'tx_vimeovideo_colcount' => [
		'exclude' => 1,
		'label'   => 'Columns',
		'config'  => [
			'type'     => 'select',
			'renderType' => 'selectSingle',
			'items'    => array(),
			'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
		],
	],
    'tx_vimeovideo_titles' => [
		'exclude' => 1,
		'label' => 'Titles',
		'config' => [
			'type' => 'check',
			'renderType' => 'checkboxToggle',
			'items' => [
				[
					0 => '',
					1 => '',
					'invertStateDisplay' => true
				]
			],
			'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
		]
	],
    'tx_vimeovideo_descriptions' => [
		'exclude' => 1,
		'label' => 'Descriptions',
		'config' => [
			'type' => 'check',
			'renderType' => 'checkboxToggle',
			'items' => [
				[
					0 => '',
					1 => '',
				]
			],
			'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
		]
	],
	'tx_vimeovideo_url'  => [
		'exclude' => 0,
		'label' => 'Vimeo URL (from earlier version)',
		'description' => 'Not for editing and not shown in web page. Use "Video" section above',
		'config' => [
            'readOnly' => true,
			'type' => 'input',
			'size' => '50',
			'eval' => 'required',
			'requiredCond' => '!field',
        ],
    ],
);

ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns);
$GLOBALS['TCA']['tt_content']['types']['vimeovideo_pi1']['previewRenderer'] = VimeovideoPreviewRenderer::class;
$GLOBALS['TCA']['tt_content']['types']['vimeovideo_pi1']['showitem'] = '
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;;general,
        --palette--;;headers,
        --palette--;LLL:EXT:vimeovideo/Resources/Private/Language/locallang_db.xlf:tx_vimeovideo.title;vimeovideoMain,
    --div--;LLL:EXT:vimeovideo/Resources/Private/Language/locallang_db.xlf:tx_vimeovideo.settings;,
        --palette--;LLL:EXT:vimeovideo/Resources/Private/Language/locallang_db.xlf:tx_vimeovideo.layout;vimeovideoLayout,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
        --palette--;;frames,
        --palette--;;appearanceLinks,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
        --palette--;;language,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
        --palette--;;hidden,
        --palette--;;access,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
        categories,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
        rowDescription,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
';

if ($extensionConfiguration['vimeovideoEnablePagination']) {
    $GLOBALS['TCA']['tt_content']['types']['vimeovideo_pi1']['showitem'] = str_replace(
        ';vimeovideoLayout,',
        ';vimeovideoLayout,
		--palette--;LLL:EXT:paginatedprocessors/Resources/Private/Language/locallang_tca.xlf:palettes.pagination;paginatedprocessors,',
        $GLOBALS['TCA']['tt_content']['types']['vimeovideo_pi1']['showitem']
    );
}

$GLOBALS['TCA']['tt_content']['palettes']['vimeovideoMain']['showitem'] = 'tx_vimeovideo_assets,--linebreak--,tx_vimeovideo_url';
$GLOBALS['TCA']['tt_content']['palettes']['vimeovideoLayout']['showitem'] = '
    tx_vimeovideo_colcount,
    tx_vimeovideo_titles,
    tx_vimeovideo_descriptions,
';

// Disable upload button in assets
$GLOBALS['TCA']['tt_content']['columns']['tx_vimeovideo_assets']['config']['appearance']['fileUploadAllowed'] = 0;
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['vimeovideo_pi1'] =  'vimeovideo_icon';
