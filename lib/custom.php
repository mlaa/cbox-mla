<?php

namespace MLA\Tuileries\Custom;

/**
 * Custom functions for the theme should go here. Use namespacing like:
 *
 * use MLA\Tuileries\Custom; (at the top of the file)
 *
 * Custom\function_name(); (in your PHP code)
 */

/**
 * Output markup listing group admins or mods, depending on what is specified
 * in $type. Modelled after bp_group_list_admins() and bp_group_list_mods().
 * but instead of outputting <ul>s and <li>s, it outputs a comma-separated
 * prose list like this: "Jonathan Reeve, Richard Stallman, Emma Goldman"
 *
 * @param object $group Optional. Group object. Default: current
 *        group in loop.
 * @param string $type Optional. Values: 'admin' or 'mod'. Default: 'admin'.
 * @return string
 */
function group_list_admins( $group = false, $type = 'admin' ) {
	global $groups_template;

	if ( empty( $group ) ) {
		$group =& $groups_template->group;
	}

	// fetch group admins if 'populate_extras' flag is false
	if ( empty( $group->args['populate_extras'] ) ) {
		$query = new BP_Group_Member_Query( array(
			'group_id'   => $group->id,
			'group_role' => $type,
			'type'       => 'first_joined',
		) );

		if ( ! empty( $query->results ) ) {
			$group->admins = $query->results;
		}
	}

	if ( ! empty( $group->admins ) ) {
		if ( count ( $group->admins ) == 1 ) {
			$admin = $group->admins[0];
			printf( '<a href="%s">%s</a>',
				bp_core_get_user_domain( $admin->user_id, $admin->user_nicename, $admin->user_login ),
				bp_core_get_user_displayname( $admin->user_id ) );
		} elseif ( count ( $group->admins ) > 1 ) {
			$admin_list_html = array();
			foreach( (array) $group->admins as $admin ) {
				$admin_html = sprintf( '<a href="%s">%s</a>',
					bp_core_get_user_domain( $admin->user_id, $admin->user_nicename, $admin->user_login ),
					bp_core_get_user_displayname( $admin->user_id ) );
				$admin_list_html[] = $admin_html;
			}
			$admins = implode( ', ', $admin_list_html );
			echo $admins;
		}
	} else { ?>
		<span class="activity"><?php _e( 'none', 'buddypress' ) ?></span>
	<?php }
}

/* Nifty function for converting numbers to words!
 * Props to Karl Rixon and this post:
 * http://www.karlrixon.co.uk/writing/convert-numbers-to-words-with-php/
 */
function convert_number_to_words($number) {

    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}
