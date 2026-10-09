<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ServiceTier;
use App\Support\Site;
use Illuminate\Http\Response;

/** robots.txt and XML sitemap (plan.md #29). */
class SeoController extends Controller
{
    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            '',
            'Sitemap: '.route('sitemap'),
        ];

        return response(implode(PHP_EOL, $lines), 200, ['Content-Type' => 'text/plain; charset=UTF-8'])
            ->header('Cache-Control', 'public, max-age=3600');
    }

    public function sitemap(): Response
    {
        $urls = collect([
            ['loc' => route('home'), 'priority' => '1.0', 'freq' => 'monthly'],
            ['loc' => route('services.index'), 'priority' => '0.9', 'freq' => 'monthly'],
            ['loc' => route('how-it-works'), 'priority' => '0.7', 'freq' => 'yearly'],
            ['loc' => route('work.index'), 'priority' => '0.8', 'freq' => 'monthly'],
            ['loc' => route('about'), 'priority' => '0.6', 'freq' => 'yearly'],
            ['loc' => route('faq'), 'priority' => '0.6', 'freq' => 'monthly'],
            ['loc' => route('contact'), 'priority' => '0.9', 'freq' => 'yearly'],
        ]);

        foreach (ServiceTier::query()->active()->ordered()->get() as $tier) {
            $urls->push(['loc' => route('services.show', $tier), 'priority' => '0.8', 'freq' => 'monthly']);
        }

        foreach (Project::query()->published()->ordered()->get() as $project) {
            $urls->push(['loc' => route('work.show', $project), 'priority' => '0.7', 'freq' => 'yearly']);
        }

        $xml = view('partials.sitemap', ['urls' => $urls, 'site' => Site::name()])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8'])
            ->header('Cache-Control', 'public, max-age=3600');
    }

    public function llms(): Response
    {
        $tiers = ServiceTier::query()->active()->ordered()->get();
        $projects = Project::query()->published()->ordered()->get();
        $lines = [
            '# Recursive Frog',
            '',
            '> Cebu-based digital studio building websites, custom software and workflow automation for businesses across industries.',
            '',
            '## Who we help',
            '',
            'Aesthetics clinics, hospitality and booking businesses, e-commerce brands, and teams with workflows that off-the-shelf software does not fit.',
            '',
            '## Services',
            '',
        ];

        foreach ($tiers as $tier) {
            $lines[] = '- ['.$tier->name.']('.route('services.show', $tier).'): '.$tier->short_description;
        }

        $lines = [...$lines, '', '## Selected work', ''];

        foreach ($projects as $project) {
            $lines[] = '- ['.$project->name.']('.route('work.show', $project).'): '.$project->summary;
        }

        $lines = [
            ...$lines,
            '',
            '## More information',
            '',
            '- [Home]('.route('home').')',
            '- [Services]('.route('services.index').')',
            '- [How it works]('.route('how-it-works').')',
            '- [About]('.route('about').')',
            '- [FAQ]('.route('faq').')',
            '- [Contact]('.route('contact').')',
            '- [XML sitemap]('.route('sitemap').')',
            '',
            '## Contact',
            '',
            'Email: '.Site::email(),
            'Phone: '.Site::phone(),
        ];

        return response(implode(PHP_EOL, $lines), 200, ['Content-Type' => 'text/plain; charset=UTF-8'])
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
