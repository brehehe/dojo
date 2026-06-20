<?php

namespace App\Providers;

use App\Models\ActiveCourtReferee;
use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\EmbuScore;
use App\Models\Group\AgeGroup;
use App\Models\Group\WeightGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Official;
use App\Models\Pool\Pool;
use App\Models\RandoriMatchResult;
use App\Models\Referee;
use App\Models\Registration;
use App\Models\Rundown\Rundown;
use App\Models\SchedulePanitera;
use App\Models\ScheduleReferee;
use App\Models\SessionTime;
use App\Models\TournamentResult;
use App\Models\User;
use App\Policies\ActiveCourtRefereePolicy;
use App\Policies\AgeGroupPolicy;
use App\Policies\AthletePolicy;
use App\Policies\ContingentPolicy;
use App\Policies\CourtPolicy;
use App\Policies\DrawingMatchNumberPolicy;
use App\Policies\EmbuScorePolicy;
use App\Policies\MatchNumberPolicy;
use App\Policies\OfficialPolicy;
use App\Policies\PoolPolicy;
use App\Policies\RandoriMatchResultPolicy;
use App\Policies\RefereePolicy;
use App\Policies\RegistrationPolicy;
use App\Policies\RundownPolicy;
use App\Policies\SchedulePaniteraPolicy;
use App\Policies\ScheduleRefereePolicy;
use App\Policies\SessionTimePolicy;
use App\Policies\TournamentResultPolicy;
use App\Policies\UserPolicy;
use App\Policies\WeightGroupPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy registry for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Athlete::class => AthletePolicy::class,
        Contingent::class => ContingentPolicy::class,
        Court::class => CourtPolicy::class,
        MatchNumber::class => MatchNumberPolicy::class,
        DrawingMatchNumber::class => DrawingMatchNumberPolicy::class,
        Registration::class => RegistrationPolicy::class,
        Referee::class => RefereePolicy::class,
        EmbuScore::class => EmbuScorePolicy::class,
        RandoriMatchResult::class => RandoriMatchResultPolicy::class,
        TournamentResult::class => TournamentResultPolicy::class,
        ScheduleReferee::class => ScheduleRefereePolicy::class,
        SchedulePanitera::class => SchedulePaniteraPolicy::class,
        Pool::class => PoolPolicy::class,
        Rundown::class => RundownPolicy::class,
        SessionTime::class => SessionTimePolicy::class,
        AgeGroup::class => AgeGroupPolicy::class,
        WeightGroup::class => WeightGroupPolicy::class,
        User::class => UserPolicy::class,
        Official::class => OfficialPolicy::class,
        ActiveCourtReferee::class => ActiveCourtRefereePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
