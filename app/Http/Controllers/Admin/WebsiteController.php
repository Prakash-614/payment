<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Models\WebsiteGatewaySetting;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    protected array $supportedGateways = ['paypal', 'stripe', 'esewa', 'khalti'];

    public function index()
    {
        $websites = Website::with('gatewaySettings')->latest()->get();
        return view('admin.websites.index', compact('websites'));
    }

    public function create()
    {
        return view('admin.websites.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:websites,domain',
            'webhook_url' => 'nullable|url',
        ]);

        $domain = preg_replace('#^https?://#', '', rtrim($validated['domain'], '/'));
        $validated['domain'] = $domain;

        $website = Website::create($validated);

        // Pre-initialize settings for supported gateways
        foreach ($this->supportedGateways as $gateway) {
            WebsiteGatewaySetting::create([
                'website_id' => $website->id,
                'gateway' => $gateway,
                'is_enabled' => false,
                'mode' => 'sandbox',
            ]);
        }

        return redirect()->route('admin.websites.edit', $website->id)
            ->with('status', "Website [{$website->name}] created. Now configure gateways.");
    }

    public function edit(Website $website)
    {
        $website->load('gatewaySettings');

        $gatewayData = [];
        foreach ($this->supportedGateways as $gateway) {
            $setting = $website->getSettingFor($gateway) ?? new WebsiteGatewaySetting([
                'website_id' => $website->id,
                'gateway' => $gateway,
                'is_enabled' => false,
                'mode' => 'sandbox',
            ]);

            $gatewayData[$gateway] = [
                'setting' => $setting,
                'sandbox' => $setting->getCredentials('sandbox'),
                'live' => $setting->getCredentials('live'),
            ];
        }

        return view('admin.websites.edit', compact('website', 'gatewayData'));
    }

    public function update(Request $request, Website $website)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:websites,domain,' . $website->id,
            'webhook_url' => 'nullable|url',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['domain'] = preg_replace('#^https?://#', '', rtrim($validated['domain'], '/'));
        $validated['is_active'] = $request->has('is_active');

        $website->update($validated);

        // Update Gateways
        $gatewaysInput = $request->input('gateways', []);

        foreach ($this->supportedGateways as $gateway) {
            $input = $gatewaysInput[$gateway] ?? [];
            $setting = WebsiteGatewaySetting::firstOrNew([
                'website_id' => $website->id,
                'gateway' => $gateway,
            ]);

            $setting->is_enabled = !empty($input['is_enabled']);
            $setting->mode = $input['mode'] ?? 'sandbox';

            $sandboxCreds = array_filter($input['sandbox'] ?? []);
            if (!empty($sandboxCreds)) {
                $setting->setCredentials('sandbox', $sandboxCreds);
            }

            $liveCreds = array_filter($input['live'] ?? []);
            if (!empty($liveCreds)) {
                $setting->setCredentials('live', $liveCreds);
            }

            $setting->save();
        }

        return redirect()->route('admin.websites.edit', $website->id)
            ->with('status', 'Website and payment settings updated successfully.');
    }
}
