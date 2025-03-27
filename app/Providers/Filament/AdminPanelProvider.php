<?php

namespace App\Providers\Filament;

use App\Models\User;
use DutchCodingCompany\FilamentSocialite\FilamentSocialitePlugin;
use DutchCodingCompany\FilamentSocialite\Provider;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\NavigationGroup;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('app')
            ->path('')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                     ->label('Records'),
                NavigationGroup::make()
                    ->label('Categories')
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugin(
                FilamentSocialitePlugin::make()
                            // (required) Add providers corresponding with providers in `config/services.php`. 
                            ->providers([
                                // Create a provider 'gitlab' corresponding to the Socialite driver with the same name.
                                Provider::make('github')
                                    ->label('Github')
                                    ->icon('fab-github')
                                    ->color(Color::hex('#1F2328'))
                                    ->outlined(false)
                                    ->stateless(false)
                                    ->scopes(['read:user'])
                            ])
                            ->registration(true)
                            ->createUserUsing(function (string $provider, SocialiteUserContract $oauthUser, FilamentSocialitePlugin $plugin): Authenticatable {
                                $user = new User();
                                $user->name = $oauthUser->getName() ?? $oauthUser->getNickname() ?? explode('@', $oauthUser->getEmail())[0];
                                $user->email = $oauthUser->getEmail();
                                $user->email_verified_at = now();
                                $user->save();
                                
                                $incomeCategories = [
                                    ['label' => '💼 Salary'],
                                    ['label' => '🏢 Business Income'],
                                    ['label' => '📈 Investments'],
                                    ['label' => '🏠 Rental Income'],
                                    ['label' => '🎁 Gifts & Donations'],
                                    ['label' => '💳 Freelance & Consulting'],
                                    ['label' => '🏦 Bank Interest'],
                                    ['label' => '🎰 Lottery & Gambling'],
                                    ['label' => '📅 Pension & Retirement'],
                                    ['label' => '🌍 Online Earnings'],
                                    ['label' => '💰 Loan']
                                ];
                                
                                $expenseCategories = [
                                    ['label' => '🍔 Food & Dining'],
                                    ['label' => '🏠 Rent & Mortgage'],
                                    ['label' => '🚗 Transportation'],
                                    ['label' => '💡 Utilities & Bills'],
                                    ['label' => '🛍️ Shopping'],
                                    ['label' => '⚕️ Healthcare & Medical'],
                                    ['label' => '🎉 Entertainment & Leisure'],
                                    ['label' => '✈️ Travel & Vacations'],
                                    ['label' => '📚 Education & Courses'],
                                    ['label' => '🎁 Gifts & Donations'],
                                    ['label' => '👶 Childcare & Parenting'],
                                    ['label' => '💳 Debt Repayment'],
                                    ['label' => '🐶 Pets & Animal Care'],
                                    ['label' => '🏋️ Fitness & Sports'],
                                    ['label' => '🛠️ Home Maintenance']
                                ];

                                $user->incomeCategories()->createMany($incomeCategories);
                                $user->expenseCategories()->createMany($expenseCategories);
                                
                                return $user;
                            })
            );
    }
}
