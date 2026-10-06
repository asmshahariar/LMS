<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackfillPartnersCommand extends Command
{
    protected $signature = 'phase4:backfill-partners';
    protected $description = 'Backfill legacy instructors as formal content partners and create course licenses';

    public function handle()
    {
        $this->info('Starting backfill of legacy instructors to content partners...');

        $instructors = \App\Models\User::whereHas('courses')->get();
        $this->info('Found ' . $instructors->count() . ' legacy instructors.');

        foreach ($instructors as $instructor) {
            $partner = \App\Models\ContentPartner::firstOrCreate(
                ['user_id' => $instructor->id],
                [
                    'name' => $instructor->name,
                    'contact_email' => $instructor->email,
                    'type' => 'individual',
                    'status' => 'active',
                    'notes' => 'Legacy instructor backfill'
                ]
            );

            // Create a default payout rule (e.g. 70% revenue share)
            $payoutRule = \App\Models\PartnerPayoutRule::firstOrCreate(
                ['content_partner_id' => $partner->id],
                [
                    'rule_type' => 'revenue_share',
                    'amount' => config('settings.commission_rate', 70), // fallback to 70 if not set
                    'currency' => 'USD',
                ]
            );

            $courses = \App\Models\Course::where('instructor_id', $instructor->id)->get();
            foreach ($courses as $course) {
                \App\Models\CourseLicense::firstOrCreate(
                    ['course_id' => $course->id, 'content_partner_id' => $partner->id],
                    [
                        'status' => 'legacy_active',
                        'start_date' => $course->created_at,
                        'exclusivity' => false,
                    ]
                );
            }
        }

        $this->info('Backfill complete!');
    }
}
