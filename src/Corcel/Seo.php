<?php

namespace WP4Laravel\Corcel;

use Corcel\Model\Option;

trait Seo
{

    /**
     * Get the SEO data from the Yoast plugin in a formatted version
     * @return Collection
     */
    public function getSeoAttribute()
    {
        if ($this instanceof \Corcel\Model\TermTaxonomy) {
            return $this->getSeoAttributeForTerms();
        }

        $meta = $this->meta->mapWithKeys(function ($item) {
            return [$item['meta_key']=>$item['meta_value']];
        });

        return collect([
            'keywords' => $meta->get('_yoast_wpseo_focuskw') ?: '',
            'title' => $meta->get('_yoast_wpseo_title') ?: $this->title,
            'description' => $meta->get('_yoast_wpseo_metadesc') ?: $this->excerpt,
            'metakeywords' => $meta->get('_yoast_wpseo_metakeywords') ?: '',
            'noindex' => $meta->get('_yoast_wpseo_meta-robots-noindex') ?: '',
            'nofollow' => $meta->get('_yoast_wpseo_meta-robots-nofollow') ?: '',
            'opengraph-title' => $meta->get('_yoast_wpseo_opengraph-title') ?: $this->title,
            'opengraph-description' => $meta->get('_yoast_wpseo_opengraph-description') ?: $this->excerpt,
            'image' => $meta->get('_yoast_wpseo_opengraph-image') ?: '',
            'twitter-title' => $meta->get('_yoast_wpseo_twitter-title') ?: '',
            'twitter-description' => $meta->get('_yoast_wpseo_twitter-description') ?: $this->excerpt,
            'twitter-image' => $meta->get('_yoast_wpseo_twitter-image') ?: '',
        ]);
    }

    protected function getSeoAttributeForTerms()
    {
        $meta = Option::get('wpseo_taxonomy_meta');

        if (!empty($meta[$this->taxonomy][$this->term_id])) {
            $data = collect($meta[$this->taxonomy][$this->term_id]);
        }

        return collect([
            'keywords' => $data['_yoast_wpseo_focuskw'] ?? '',
            'title' => $data['wpseo_title'] ?? $this->title,
            'description' => $data['wpseo_title'] ?? $this->description,
            'metakeywords' =>  '',
            'noindex' => $data['wpseo_noindex'] ?? '',
            'nofollow' => $data['wpseo_nofollow'] ?? '',
            'opengraph-title' => $data['wpseo_opengraph-title'] ?? $this->title,
            'opengraph-description' => $data['wpseo_opengraph-description'] ?? $this->description,
            'image' => $data['wpseo_opengraph-image'] ?? '',
            'twitter-title' => $data['wpseo_twitter-title'] ?? '',
            'twitter-description' => $data['wpseo_twitter-description'] ?? $this->description,
            'twitter-image' => $data['wpseo_twitter-image'] ?? '',
        ]);
    }
}
