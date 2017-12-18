<?php
declare(strict_types = 1);
namespace App\Controller\Styleguide\Amen\Domain;

use App\Builders\ClipBuilder;
use App\Builders\EpisodeBuilder;
use App\Controller\BaseController;
use App\Builders\BrandBuilder;
use App\Builders\SeriesBuilder;
use BBC\ProgrammesPagesService\Domain\ValueObject\PartialDate;

class ProgrammeController extends BaseController
{
    public function __invoke()
    {
        return $this->renderWithChrome('styleguide/amen/domain/programme.html.twig', [
            'itemsOfDifferentTypes' => $this->buildProgrammesItemsOfDifferentTypes(),
            'itemsWithDifferentDisplayOptions' => $this->buildItemsWithDifferentDisplays(),
            'itemsWithDifferentHtmlStructure' => $this->buildItemsWithDifferentHtmlStructure(),

        ]);
    }

    /**
     * Use case: The user see two different programmes
     * @return array
     */
    private function buildProgrammesItemsOfDifferentTypes()
    {
        return [
            // programme items
            'Programme item is Episode' => [
                'item' => EpisodeBuilder::default()->build(),
                'render_options' => [],
            ],
            'Programme item is Clip' => [
                'item' => ClipBuilder::default()->build(),
                'render_options' => [],
            ],
            // programme containers
            'Programme container is Brand' => [
                'item' => BrandBuilder::default()->build(),
                'render_options' => [],
            ],
            'Programme container is Series' => [
                'item' => SeriesBuilder::default()->build(),
                'render_options' => [],
            ],
        ];
    }

    /**
     * Use case: The user see the same programme, but each one displays different fields. However they are the same.
     * They have different displays only.
     *
     * @return array
     */
    private function buildItemsWithDifferentDisplays()
    {
        return [
            'TYPE VARIATION' => [
                'item' => ClipBuilder::default()->build(),
                'render_options' => [
                    'show_image' => false,
                ],
            ],
            'TITLE VARIATION 1' => [
                'item' => ClipBuilder::default()->build(),
                'render_options' => [
                    'title_options' => [
                        'h_tag' => 'h1',
                        'text_colour_on_title_link' => false,
                        'title_size_large' => 'gel-trafalgar',
                        'title_size_small' => 'gel-pica',
                        'max_title_length' => 6,
                    ]
                ],
            ],
            'BODY VARIATION' => [
                'item' => ClipBuilder::default()->withReleaseDate(new PartialDate(2020, 03, 21))->build(),
                'render_options' => [
                    'body_options' => [
                        'show_synopsis' => true,
                        'show_release_date' => true,
                    ]
                ],
            ],
            'CTA VARIATION (call to action)' => [
                'item' => ClipBuilder::default()->build(),
                'render_options' => [
                    'cta_options' => [
                        'show_duration' => false,
                    ]
                ],
            ],
            'IMAGE VARIATION' => [
                'item' => ClipBuilder::default()->build(),
                'render_options' => [
                    'image_options' => [
                        // classes & elements
                        'media_panel_class' => '1/2',
                        // badge to overlay the top of the image
                        'badge_text' => 'some badge text',
                        'badge_class' => 'br-box-highlight',
                    ],
                ],
            ],
        ];
    }

    public function buildItemsWithDifferentHtmlStructure()
    {

    }

    // private function buildItemsWithDifferentLayout()
    // {
    //     //$renderOptions = [
    //          BaseCoreEntity
    //          'branding_name' => 'subtle',
    //          'context_programme' => null,
    //          'media_variant' => 'media--column media--card',
    //          'media_details_class' => 'media__details',
    //          'show_image' => true,
    //          'force_iplayer_linking' => false,
    //          'link_location_prefix' => 'programmeobject_',
    //
    //          // Subpresenter options
    //          'body_options' => [
    //              // BaseBodyPresenter
    //              'show_synopsis' => false,
    //              'synopsis_class' => 'invisible visible@gel3',
    //              'show_release_date' => false,
    //              'full_details_class' => 'programme__details',
    //          ],
    //          'cta_options' => [
    //              // BaseCtaPresenter
    //              'cta_class' => 'cta cta--dark',
    //              'link_location_prefix' => 'programmeobject_',
    //              'show_duration' => true,
    //          ],
    //          'image_options' => [
    //              //ImageBasePresenter
    //              'show_image' => true,
    //              'is_lazy_loaded' => true,
    //              'force_iplayer_linking' => false,
    //              'default_width' => 320,
    //              'sizes' => [
    //                  // @TODO confirm these are the right sizes
    //                  0 => '0vw',
    //                  320 => 1 / 4,
    //                  480 => 1 / 4,
    //                  600 => 1 / 3,
    //                  1008 => '336px',
    //                  1280 => '432px',
    //              ],
    //              // classes & elements
    //              'media_panel_class' => '1/1',
    //
    //              // badge to overlay the top of the image
    //              'badge_text' => '',
    //              'badge_class' => 'br-box-highlight',
    //              'cta_options' => [],
    //          ],
    //          'title_options' => [
    //              // BaseTitlePresenter
    //              'h_tag' => 'h4',
    //              'text_colour_on_title_link' => true,
    //              'title_format' => 'item::ancestry',
    //              'title_size_large' => 'gel-pica-bold',
    //              'title_size_small' => 'gel-pica',
    //              'branding_name' => 'subtle',
    //              'max_title_length' => 60,
    //          ],
    // }
}
