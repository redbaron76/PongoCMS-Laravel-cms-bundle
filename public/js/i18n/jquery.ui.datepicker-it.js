/* Italian initialisation for the jQuery UI date picker plugin. */

jQuery(function($){
	
	if($.datepicker) {

		$.datepicker.regional['it'] = {

			closeText: 'Chiudi',
			prevText: '&#x3c;Prec',
			nextText: 'Succ&#x3e;',
			currentText: 'Oggi',
			monthNames: ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno',
				'Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'],
			monthNamesShort: ['Gen','Feb','Mar','Apr','Mag','Giu',
				'Lug','Ago','Set','Ott','Nov','Dic'],
			dayNames: ['Domenica','Luned&#236','Marted&#236','Mercoled&#236','Gioved&#236','Venerd&#236','Sabato'],
			dayNamesShort: ['Dom','Lun','Mar','Mer','Gio','Ven','Sab'],
			dayNamesMin: ['Do','Lu','Ma','Me','Gi','Ve','Sa'],
			weekHeader: 'Sm',
			dateFormat: 'dd/mm/yy',
			firstDay: 1,
			isRTL: false,
			showMonthAfterYear: false,
			yearSuffix: ''

		};

		$.datepicker.setDefaults($.datepicker.regional['it']);

	}

/* Italian initialisation for the jQuery UI time picker plugin. */

	if($.timepicker) {

		$.timepicker.regional['it'] = {

			currentText: 'Adesso',
			closeText: 'Chiudi',
			ampm: false,
			amNames: ['AM', 'A'],
			pmNames: ['PM', 'P'],
			timeFormat: 'hh:mm tt',
			timeSuffix: '',
			timeOnlyTitle: 'Choose Time',
			timeText: 'Ora',
			hourText: 'Ore',
			minuteText: 'Minuti',
			secondText: 'Secondi',
			millisecText: 'Millisecondi',
			timezoneText: 'Time Zone'

		};

		$.timepicker.setDefaults($.timepicker.regional['it']);

	}

});
