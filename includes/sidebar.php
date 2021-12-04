<?php

function cos_sidebar( string $sidebar ) {

	switch ($sidebar) {
		case 'K-12':
			$sidebarOption = 'k12_sidebar';
			break;

		case 'College Students':
			$sidebarOption = 'college_student_sidebar';
			break;

		case 'Faculty':
			$sidebarOption = 'faculty_sidebar';
			break;

		case 'ISTEM':
			$sidebarOption = 'istem_sidebar';
			break;
	}

	return $sidebarOption;
}
