  jQuery(document).ready(function() {
            jQuery('#copyText').click(function() {
                var reju = jQuery('#shortcodename').val();
                var $temp = jQuery("<input>");
                jQuery("body").append($temp);
                $temp.val(reju).select();
                document.execCommand("copy");
                $temp.remove();
                jQuery('#successMessage').text('Coppied Successfully');
                jQuery("#successMessage").show('slow').delay(5000).hide('slow');
            })
            jQuery(function() {
                jQuery('.cp-basic').colorpicker();
            });
        });