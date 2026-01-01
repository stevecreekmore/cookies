{{-- Cookie Consent Banner --}}
<div id="cookie-consent-banner" style="display: none;">
    <div class="cookie-consent-wrapper">
        {{-- Main Consent Content --}}
        <div class="cookie-consent-content">
            <h3 class="cookie-consent-title">{{ config('cookies.text.title') }}</h3>
            <p class="cookie-consent-description">
                {{ config('cookies.text.description') }}
                @if(config('cookies.cookie_privacy_policy_url'))
                    <a href="{{ config('cookies.cookie_privacy_policy_url') }}" target="_blank" class="cookie-consent-link">Privacy Policy</a>
                @endif
                @if(config('cookies.cookie_policy_url'))
                    | <a href="{{ config('cookies.cookie_policy_url') }}" target="_blank" class="cookie-consent-link">Cookie Policy</a>
                @endif
            </p>

            <div class="cookie-consent-actions">
                <button id="cookie-consent-accept-all" class="cookie-consent-btn cookie-consent-btn-primary">
                    {{ config('cookies.text.accept_all') }}
                </button>
                <button id="cookie-consent-manage" class="cookie-consent-btn cookie-consent-btn-secondary">
                    {{ config('cookies.text.manage_preferences') }}
                </button>
                <button id="cookie-consent-reject-all" class="cookie-consent-btn cookie-consent-btn-tertiary">
                    {{ config('cookies.text.reject_all') }}
                </button>
            </div>
        </div>

        {{-- Preferences Panel --}}
        <div id="cookie-consent-preferences" style="display: none;">
            <div class="cookie-consent-categories">
                @foreach(config('cookies.categories', []) as $key => $category)
                    @if($category['enabled'])
                        <div class="cookie-consent-category">
                            <label class="cookie-consent-label">
                                <input
                                    type="checkbox"
                                    name="cookie_category[]"
                                    value="{{ $key }}"
                                    {{ $category['required'] ? 'checked disabled' : '' }}
                                    class="cookie-consent-checkbox"
                                >
                                <span class="cookie-consent-category-name">
                                    {{ $category['label'] }}
                                    @if($category['required'])
                                        <span class="cookie-consent-required">(Required)</span>
                                    @endif
                                </span>
                            </label>
                            <p class="cookie-consent-category-description">{{ $category['description'] }}</p>

                            {{-- Show cookie details if available --}}
                            @if(!empty($category['cookies']))
                                <button type="button" class="cookie-details-toggle cookie-consent-link" data-category="{{ $key }}">
                                    {{ config('cookies.text.view_details') }} ▼
                                </button>
                                <div class="cookie-details" id="cookie-details-{{ $key }}" style="display: none;">
                                    <table class="cookie-details-table">
                                        <thead>
                                            <tr>
                                                <th>Cookie Name</th>
                                                <th>Purpose</th>
                                                <th>Duration</th>
                                                <th>Provider</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($category['cookies'] as $cookie)
                                                <tr>
                                                    <td>{{ $cookie['name'] ?? 'N/A' }}</td>
                                                    <td>{{ $cookie['purpose'] ?? 'N/A' }}</td>
                                                    <td>{{ $cookie['duration'] ?? 'N/A' }}</td>
                                                    <td>{{ $cookie['provider'] ?? 'N/A' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="cookie-consent-actions">
                <button id="cookie-consent-save" class="cookie-consent-btn cookie-consent-btn-primary">
                    {{ config('cookies.text.save_preferences') }}
                </button>
                <button id="cookie-consent-back" class="cookie-consent-btn cookie-consent-btn-secondary">
                    Back
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Floating Cookie Settings Button (appears after consent given) --}}
@if(config('cookies.show_settings_button', true))
<button id="cookie-settings-button" class="cookie-settings-floating" style="display: none;" aria-label="Cookie Settings">
    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
        <path d="M10 2C8.9 2 8 2.9 8 4C8 4.3 8.1 4.6 8.2 4.9C6.6 5.5 5.4 6.9 5 8.6C4.7 8.5 4.4 8.5 4 8.5C2.9 8.5 2 9.4 2 10.5C2 11.6 2.9 12.5 4 12.5C4.4 12.5 4.7 12.4 5 12.3C5.4 14 6.6 15.4 8.2 16C8.1 16.3 8 16.6 8 17C8 18.1 8.9 19 10 19C11.1 19 12 18.1 12 17C12 16.6 11.9 16.3 11.8 16C13.4 15.4 14.6 14 15 12.3C15.3 12.4 15.6 12.5 16 12.5C17.1 12.5 18 11.6 18 10.5C18 9.4 17.1 8.5 16 8.5C15.6 8.5 15.3 8.6 15 8.7C14.6 7 13.4 5.6 11.8 5C11.9 4.7 12 4.4 12 4C12 2.9 11.1 2 10 2Z"/>
    </svg>
</button>
@endif

<style>
#cookie-consent-banner {
    position: fixed;
    {{ config('cookies.styling.position') === 'top' ? 'top: 0;' : 'bottom: 0;' }}
    left: 0;
    right: 0;
    background: {{ config('cookies.styling.theme') === 'dark' ? '#1a1a1a' : '#ffffff' }};
    color: {{ config('cookies.styling.theme') === 'dark' ? '#ffffff' : '#333333' }};
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
    z-index: 999999;
    padding: 20px;
    max-height: 90vh;
    overflow-y: auto;
}

.cookie-consent-wrapper {
    max-width: 1200px;
    margin: 0 auto;
}

.cookie-consent-title {
    font-size: 1.5rem;
    margin: 0 0 10px 0;
    font-weight: 600;
}

.cookie-consent-description {
    margin: 0 0 20px 0;
    line-height: 1.5;
}

.cookie-consent-link {
    color: #007bff;
    text-decoration: underline;
    background: none;
    border: none;
    cursor: pointer;
    font-size: inherit;
    padding: 0;
}

.cookie-consent-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.cookie-consent-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s;
}

.cookie-consent-btn-primary {
    background: #007bff;
    color: white;
}

.cookie-consent-btn-primary:hover {
    background: #0056b3;
}

.cookie-consent-btn-secondary {
    background: #6c757d;
    color: white;
}

.cookie-consent-btn-secondary:hover {
    background: #545b62;
}

.cookie-consent-btn-tertiary {
    background: transparent;
    color: {{ config('cookies.styling.theme') === 'dark' ? '#ffffff' : '#333333' }};
    border: 1px solid {{ config('cookies.styling.theme') === 'dark' ? '#ffffff' : '#cccccc' }};
}

.cookie-consent-categories {
    margin-bottom: 20px;
}

.cookie-consent-category {
    margin-bottom: 15px;
    padding: 15px;
    background: {{ config('cookies.styling.theme') === 'dark' ? '#2a2a2a' : '#f8f9fa' }};
    border-radius: 4px;
}

.cookie-consent-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-weight: 600;
    margin-bottom: 5px;
}

.cookie-consent-checkbox {
    margin-right: 10px;
}

.cookie-consent-required {
    font-size: 0.8rem;
    font-weight: normal;
    opacity: 0.7;
    margin-left: 5px;
}

.cookie-consent-category-description {
    margin: 5px 0 10px 0;
    font-size: 0.9rem;
    opacity: 0.8;
}

.cookie-details-toggle {
    margin-top: 5px;
    font-size: 0.85rem;
}

.cookie-details {
    margin-top: 10px;
    overflow-x: auto;
}

.cookie-details-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
}

.cookie-details-table th,
.cookie-details-table td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid {{ config('cookies.styling.theme') === 'dark' ? '#444' : '#ddd' }};
}

.cookie-details-table th {
    font-weight: 600;
    background: {{ config('cookies.styling.theme') === 'dark' ? '#333' : '#e9ecef' }};
}

.cookie-settings-floating {
    position: fixed;
    bottom: 20px;
    left: 20px;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #007bff;
    color: white;
    border: none;
    cursor: pointer;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    z-index: 999998;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.cookie-settings-floating:hover {
    background: #0056b3;
    transform: scale(1.1);
}
</style>

<script>
(function() {
    'use strict';

    const COOKIE_NAME = '{{ config('cookies.cookie_name') }}';
    const COOKIE_LIFETIME = {{ config('cookies.cookie_lifetime') }};
    const LOG_CONSENT = {{ config('cookies.log_consent', true) ? 'true' : 'false' }};

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    function setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = `expires=${date.toUTCString()}`;
        document.cookie = `${name}=${value};${expires};path=/;SameSite=Lax`;
    }

    function deleteCookie(name) {
        document.cookie = `${name}=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/;`;
    }

    function generateUUID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            const r = Math.random() * 16 | 0;
            const v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    function getConsentId() {
        const cookie = getCookie(COOKIE_NAME);
        if (cookie) {
            try {
                const data = JSON.parse(decodeURIComponent(cookie));
                return data.id || generateUUID();
            } catch (e) {
                return generateUUID();
            }
        }
        return generateUUID();
    }

    function showBanner() {
        document.getElementById('cookie-consent-banner').style.display = 'block';
        const settingsBtn = document.getElementById('cookie-settings-button');
        if (settingsBtn) settingsBtn.style.display = 'none';

        // Reset to main view (not preferences)
        hidePreferences();
    }

    function hideBanner() {
        document.getElementById('cookie-consent-banner').style.display = 'none';
        const settingsBtn = document.getElementById('cookie-settings-button');
        if (settingsBtn) settingsBtn.style.display = 'flex';
    }

    function showPreferences() {
        document.querySelector('.cookie-consent-content').style.display = 'none';
        document.getElementById('cookie-consent-preferences').style.display = 'block';
    }

    function hidePreferences() {
        document.querySelector('.cookie-consent-content').style.display = 'block';
        document.getElementById('cookie-consent-preferences').style.display = 'none';
    }

    function logConsent(categories, action) {
        if (!LOG_CONSENT) return;

        fetch('/api/cookie-consent/log', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: JSON.stringify({ categories, action }),
        }).catch(err => console.error('Failed to log consent:', err));
    }

    function saveConsent(categories, action = 'custom') {
        const consentId = getConsentId();
        const consentData = {
            id: consentId,
            categories: categories,
            timestamp: new Date().toISOString(),
        };

        setCookie(COOKIE_NAME, encodeURIComponent(JSON.stringify(consentData)), COOKIE_LIFETIME);
        hideBanner();

        // Log to server
        logConsent(categories, action);

        // Load scripts based on consent
        loadConsentedScripts(categories);

        // Dispatch custom event
        window.dispatchEvent(new CustomEvent('cookieConsentChanged', {
            detail: { categories, action, id: consentId }
        }));
    }

    function loadConsentedScripts(categories) {
        // Find all blocked scripts and load those that now have consent
        const scripts = document.querySelectorAll('script[type="text/plain"][data-cookie-consent]');

        scripts.forEach(script => {
            const requiredCategory = script.getAttribute('data-cookie-consent');

            if (categories.includes(requiredCategory)) {
                // Create new script element
                const newScript = document.createElement('script');

                // Copy attributes
                Array.from(script.attributes).forEach(attr => {
                    if (attr.name !== 'type' && attr.name !== 'data-cookie-consent') {
                        newScript.setAttribute(attr.name, attr.value);
                    }
                });

                // Copy content
                newScript.textContent = script.textContent;

                // Replace old script with new one
                script.parentNode.replaceChild(newScript, script);
            }
        });
    }

    function withdrawConsent() {
        if (confirm('Are you sure you want to withdraw your cookie consent? This will delete non-essential cookies and reload the page.')) {
            // Log withdrawal
            if (LOG_CONSENT) {
                fetch('/api/cookie-consent/withdraw', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                }).catch(err => console.error('Failed to log withdrawal:', err));
            }

            // Delete consent cookie
            deleteCookie(COOKIE_NAME);

            // Reload page to clear scripts
            window.location.reload();
        }
    }

    // Check if consent already given
    const existingConsent = getCookie(COOKIE_NAME);
    if (!existingConsent) {
        showBanner();
    } else {
        // Load scripts based on existing consent
        try {
            const consentData = JSON.parse(decodeURIComponent(existingConsent));
            if (consentData.categories) {
                loadConsentedScripts(consentData.categories);
            }
        } catch (e) {
            console.error('Failed to parse consent cookie:', e);
        }

        // Show settings button if enabled
        const settingsBtn = document.getElementById('cookie-settings-button');
        if (settingsBtn) settingsBtn.style.display = 'flex';
    }

    // Event listeners
    document.getElementById('cookie-consent-accept-all')?.addEventListener('click', function() {
        const allCategories = @json(array_keys(config('cookies.categories', [])));
        saveConsent(allCategories, 'accept_all');
    });

    document.getElementById('cookie-consent-reject-all')?.addEventListener('click', function() {
        const requiredCategories = @json(array_keys(array_filter(config('cookies.categories', []), fn($cat) => $cat['required'] ?? false)));
        saveConsent(requiredCategories, 'reject_all');
    });

    document.getElementById('cookie-consent-manage')?.addEventListener('click', function() {
        showPreferences();
    });

    document.getElementById('cookie-consent-back')?.addEventListener('click', function() {
        hidePreferences();
    });

    document.getElementById('cookie-consent-save')?.addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('input[name="cookie_category[]"]:checked');
        const categories = Array.from(checkboxes).map(cb => cb.value);
        saveConsent(categories, 'custom');
    });

    // Cookie details toggles
    document.querySelectorAll('.cookie-details-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            const details = document.getElementById('cookie-details-' + category);

            if (details.style.display === 'none') {
                details.style.display = 'block';
                this.textContent = this.textContent.replace('▼', '▲');
            } else {
                details.style.display = 'none';
                this.textContent = this.textContent.replace('▲', '▼');
            }
        });
    });

    // Settings button click handler
    document.getElementById('cookie-settings-button')?.addEventListener('click', function() {
        showBanner();
    });

    // Expose showBanner globally so it can be called from anywhere
    window.CookieConsent = {
        showBanner: showBanner,
        hideBanner: hideBanner,
        withdrawConsent: withdrawConsent,
        getConsent: function() {
            const cookie = getCookie(COOKIE_NAME);
            if (!cookie) return null;
            try {
                return JSON.parse(decodeURIComponent(cookie));
            } catch (e) {
                return null;
            }
        }
    };
})();
</script>
