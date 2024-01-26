<?php


namespace App\Utils;

class PackageValidation
{
    static public function packageValidation(
        $packages,
        $package_reimbursement_month_year,
        $package_received_month_year,
        $package_no,
        $package_reimbursement_month_year_key,
        $package_received_month_year_key,
    ) {
        $message = '';

        if ($package_reimbursement_month_year && $package_received_month_year) {
            $rq_month_years = explode("|", $package_reimbursement_month_year);

            foreach ($rq_month_years as $rq_month_year) {
                if ($package_received_month_year == $rq_month_year) {
                    $message = $package_received_month_year . 'has already existed in selected package ' . $package_no . ' reimbursement month year!';
                }
            }
        }
        if ($package_reimbursement_month_year) {
            $rq_month_years = explode("|", $package_reimbursement_month_year);

            foreach ($packages as $p) {
                if ($p->{$package_reimbursement_month_year_key}) {
                    $month_years = explode("|", $p->{$package_reimbursement_month_year_key});

                    foreach ($month_years as $month_year) {
                        foreach ($rq_month_years as $rq_month_year) {
                            if ($month_year == $rq_month_year) {
                                $message = $rq_month_year . 'has already existed in package ' . $package_no . ' reimbursement month year!';
                            }
                        }
                    }
                }
                if ($p->{$package_received_month_year_key}) {
                    foreach ($rq_month_years as $rq_month_year) {
                        if ($p->{$package_received_month_year_key} == $rq_month_year) {
                            $message = $rq_month_year . 'has already existed in package ' . $package_no . ' reimbursement month year!';
                        }
                    }
                }
            }
        }
        if ($package_received_month_year) {
            foreach ($packages as $p) {
                if ($p->{$package_reimbursement_month_year_key}) {
                    $month_years = explode("|", $p->{$package_reimbursement_month_year_key});

                    foreach ($month_years as $month_year) {
                        if ($month_year == $package_received_month_year) {
                            $message = $package_received_month_year . 'has already existed in package ' . $package_no . ' reimbursement month year!';
                        }
                    }
                }
                if ($p->{$package_received_month_year_key}) {
                    if ($p->{$package_received_month_year_key} == $package_received_month_year) {
                        $message = $package_received_month_year . 'has already existed in package ' . $package_no . ' reimbursement month year!';
                    }
                }
            }
        }
        return $message;
    }
}
