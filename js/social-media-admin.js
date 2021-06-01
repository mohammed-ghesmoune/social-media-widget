
let $ = jQuery;
const widget = {

  addSocialMedia(element) {

    let widget_id = $(element).parent().parent().attr('id');
    let widget_number = $(element).parent().parent().data('widget-number');
    let css_class = $(element).data('class');
    let n = $("#" + widget_id + " .sm-input-wrapper ").length;

    let inputs = '<div style="display:flex; padding:3px 0px;" class="sm-input-wrapper">'
    inputs += '<div class="widget-social-icons">'
    inputs += '<div class="social-icon"><i class="' + css_class + '"></i></div>'
    inputs += '</div>'
    inputs += '<input class="widefat"  name="widget-social-media[' + widget_number + '][social_media][' + n + '][class]" type="hidden" value="' + css_class + '">'
    inputs += '<input class="widefat"  name="widget-social-media[' + widget_number + '][social_media][' + n + '][href]" type="text" value="" placeholder="Enter link url" >'
    inputs += '<div class="widget-social-icons">'
    inputs += '<div class="delete" onclick="widget.deleteSocialMedia(this)"><i class="fas fa-times"></i></div>'
    inputs += '</div></div>';


    $("#" + widget_id + " #widget-social-icons").after($(inputs));
    //$(element).closest('form').find('input[type="submit"]').click();
  },

  deleteSocialMedia(element) {
    let submitButton = $(element).closest('form').find('input[type="submit"]');
    $(element).closest('.sm-input-wrapper').remove();
    submitButton.click();
  }

}

