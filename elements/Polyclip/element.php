<?php

namespace Polyclip;

use function Breakdance\Elements\c;
use function Breakdance\Elements\PresetSections\getPresetSection;


\Breakdance\ElementStudio\registerElementForEditing(
    "Polyclip\\PolyclipElement",
    \Breakdance\Util\getdirectoryPathRelativeToPluginFolder(__DIR__)
);

class PolyclipElement extends \Breakdance\Elements\Element
{
    static function uiIcon()
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="1 6 4 2 12 2 20 2 23 6 23 18 20 22 4 22 1 18"/></svg>';
    }

    static function availableIn()
    {
        return ['breakdance', 'oxygen'];
    }

    static function tag()
    {
        return 'div';
    }

    static function tagOptions()
    {
        return ['div', 'section', 'header', 'article'];
    }

    static function tagControlPath()
    {
        return false;
    }

    static function name()
    {
        return 'Polyclip';
    }

    static function className()
    {
        return 'polyclip-bd-element';
    }

    static function category()
    {
        return 'blocks';
    }

    static function badge()
    {
        return ['label' => 'PC', 'textColor' => 'var(--white)', 'backgroundColor' => '#1e40af'];
    }

    static function slug()
    {
        return __CLASS__;
    }

    static function template()
    {
        return file_get_contents(__DIR__ . '/html.twig');
    }

    static function defaultCss()
    {
        return '';
    }

    static function defaultProperties()
    {
        return [
            'content' => [
                'animation' => [
                    'preset'   => 'default',
                    'animate'  => true,
                    'duration' => '8',
                ],
                'aspect' => [
                    'width'  => '1800',
                    'height' => '780',
                ],
            ],
        ];
    }

    static function defaultChildren()
    {
        return false;
    }

    static function cssTemplate()
    {
        return file_get_contents(__DIR__ . '/css.twig');
    }

    static function contentControls()
    {
        return [
            // -- Images --
            c(
                'images',
                'Images',
                [
                    c(
                        'universal',
                        'All Breakpoints',
                        [c(
                            'image',
                            'Image',
                            [],
                            ['type' => 'wpmedia', 'layout' => 'vertical', 'mediaOptions' => ['acceptedFileTypes' => ['image'], 'multiple' => false]],
                            false,
                            false,
                            []
                        )],
                        ['type' => 'repeater', 'layout' => 'vertical', 'repeaterOptions' => ['titleTemplate' => '', 'defaultTitle' => '', 'buttonName' => 'Add Image', 'galleryMode' => true, 'galleryMediaPath' => 'image']],
                        false,
                        false,
                        []
                    ),
                    c(
                        'desktop',
                        'Desktop (>=1025px)',
                        [c(
                            'image',
                            'Image',
                            [],
                            ['type' => 'wpmedia', 'layout' => 'vertical', 'mediaOptions' => ['acceptedFileTypes' => ['image'], 'multiple' => false]],
                            false,
                            false,
                            []
                        )],
                        ['type' => 'repeater', 'layout' => 'vertical', 'repeaterOptions' => ['titleTemplate' => '', 'defaultTitle' => '', 'buttonName' => 'Add Image', 'galleryMode' => true, 'galleryMediaPath' => 'image']],
                        false,
                        false,
                        []
                    ),
                    c(
                        'tablet',
                        'Tablet (768-1024px)',
                        [c(
                            'image',
                            'Image',
                            [],
                            ['type' => 'wpmedia', 'layout' => 'vertical', 'mediaOptions' => ['acceptedFileTypes' => ['image'], 'multiple' => false]],
                            false,
                            false,
                            []
                        )],
                        ['type' => 'repeater', 'layout' => 'vertical', 'repeaterOptions' => ['titleTemplate' => '', 'defaultTitle' => '', 'buttonName' => 'Add Image', 'galleryMode' => true, 'galleryMediaPath' => 'image']],
                        false,
                        false,
                        []
                    ),
                    c(
                        'mobile',
                        'Mobile (<=767px)',
                        [c(
                            'image',
                            'Image',
                            [],
                            ['type' => 'wpmedia', 'layout' => 'vertical', 'mediaOptions' => ['acceptedFileTypes' => ['image'], 'multiple' => false]],
                            false,
                            false,
                            []
                        )],
                        ['type' => 'repeater', 'layout' => 'vertical', 'repeaterOptions' => ['titleTemplate' => '', 'defaultTitle' => '', 'buttonName' => 'Add Image', 'galleryMode' => true, 'galleryMediaPath' => 'image']],
                        false,
                        false,
                        []
                    ),
                    c(
                        'image_position',
                        'Image Position',
                        [],
                        [
                            'type'    => 'dropdown',
                            'layout'  => 'inline',
                            'items'   => [
                                ['value' => 'center',        'text' => 'Center'],
                                ['value' => 'top',           'text' => 'Top'],
                                ['value' => 'bottom',        'text' => 'Bottom'],
                                ['value' => 'left',          'text' => 'Left'],
                                ['value' => 'right',         'text' => 'Right'],
                                ['value' => 'center top',    'text' => 'Center Top'],
                                ['value' => 'center bottom', 'text' => 'Center Bottom'],
                            ],
                        ],
                        false,
                        false,
                        []
                    ),
                    c(
                        'alt',
                        'Alt Text',
                        [],
                        ['type' => 'text', 'layout' => 'vertical', 'placeholder' => 'Descriptive alt text'],
                        false,
                        false,
                        []
                    ),
                ],
                ['type' => 'section', 'layout' => 'vertical'],
                false,
                false,
                []
            ),
            // -- Animation --
            c(
                'animation',
                'Animation',
                [
                    c(
                        'preset',
                        'Preset',
                        [],
                        [
                            'type'    => 'dropdown',
                            'layout'  => 'vertical',
                            'items'   => [
                                ['value' => 'default',       'text' => 'Default'],
                                ['value' => 'angular-left',  'text' => 'Angular Left'],
                                ['value' => 'angular-right', 'text' => 'Angular Right'],
                                ['value' => 'wide',          'text' => 'Wide'],
                                ['value' => 'shard',         'text' => 'Shard'],
                                ['value' => 'blob',          'text' => 'Blob'],
                            ],
                        ],
                        false,
                        false,
                        []
                    ),
                    c(
                        'animate',
                        'Animate',
                        [],
                        ['type' => 'toggle', 'layout' => 'inline'],
                        false,
                        false,
                        []
                    ),
                    c(
                        'duration',
                        'Duration (seconds)',
                        [],
                        ['type' => 'text', 'layout' => 'inline', 'placeholder' => '8'],
                        false,
                        false,
                        []
                    ),
                    c(
                        'safe_zone',
                        'Safe Zone (%)',
                        [],
                        ['type' => 'text', 'layout' => 'inline', 'placeholder' => '0'],
                        false,
                        false,
                        []
                    ),
                ],
                ['type' => 'section', 'layout' => 'vertical'],
                false,
                false,
                []
            ),
            // -- Aspect Ratio --
            c(
                'aspect',
                'Aspect Ratio',
                [
                    c('width', 'Default Width', [], ['type' => 'text', 'layout' => 'inline', 'placeholder' => '1800'], false, false, []),
                    c('height', 'Default Height', [], ['type' => 'text', 'layout' => 'inline', 'placeholder' => '780'], false, false, []),
                    c('desktop_width', 'Desktop Width', [], ['type' => 'text', 'layout' => 'inline', 'placeholder' => '1800'], false, false, []),
                    c('desktop_height', 'Desktop Height', [], ['type' => 'text', 'layout' => 'inline', 'placeholder' => '300'], false, false, []),
                    c('tablet_width', 'Tablet Width', [], ['type' => 'text', 'layout' => 'inline', 'placeholder' => '1024'], false, false, []),
                    c('tablet_height', 'Tablet Height', [], ['type' => 'text', 'layout' => 'inline', 'placeholder' => '300'], false, false, []),
                    c('mobile_width', 'Mobile Width', [], ['type' => 'text', 'layout' => 'inline', 'placeholder' => '460'], false, false, []),
                    c('mobile_height', 'Mobile Height', [], ['type' => 'text', 'layout' => 'inline', 'placeholder' => '500'], false, false, []),
                ],
                ['type' => 'section', 'layout' => 'vertical'],
                false,
                false,
                []
            ),
        ];
    }

    static function designControls() { return []; }
    static function settingsControls() { return []; }

    static function dependencies()
    {
        return [
            [
                'title'  => 'Polyclip CSS',
                'styles' => [POLYCLIP_URL . 'assets/css/polyclip.css'],
            ],
            [
                'title'   => 'Polyclip JS',
                'scripts' => [POLYCLIP_URL . 'assets/js/polyclip-animation.js'],
            ],
        ];
    }

    static function settings() { return false; }
    static function addPanelRules() { return false; }
    static public function actions() { return false; }
    static function nestingRule() { return ['type' => 'final']; }
    static function spacingBars() { return ['0' => ['location' => 'outside-top', 'cssProperty' => 'margin-top', 'affectedPropertyPath' => 'design.spacing.margin_top.%%BREAKPOINT%%'], '1' => ['location' => 'outside-bottom', 'cssProperty' => 'margin-bottom', 'affectedPropertyPath' => 'design.spacing.margin_bottom.%%BREAKPOINT%%']]; }
    static function attributes() { return false; }
    static function experimental() { return false; }
    static function order() { return 100; }
    static function dynamicPropertyPaths() { return false; }
    static function additionalClasses() { return false; }
    static function projectManagement() { return false; }
    static function propertyPathsToWhitelistInFlatProps() { return false; }

    static function propertyPathsToSsrElementWhenValueChanges()
    {
        return [
            'content.images.universal',
            'content.images.desktop',
            'content.images.tablet',
            'content.images.mobile',
            'content.images.image_position',
            'content.images.alt',
            'content.animation.preset',
            'content.animation.animate',
            'content.animation.duration',
            'content.animation.safe_zone',
            'content.aspect.width',
            'content.aspect.height',
            'content.aspect.desktop_width',
            'content.aspect.desktop_height',
            'content.aspect.tablet_width',
            'content.aspect.tablet_height',
            'content.aspect.mobile_width',
            'content.aspect.mobile_height',
        ];
    }
}
