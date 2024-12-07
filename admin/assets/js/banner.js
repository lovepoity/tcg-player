$(document).ready(function() {
  initializeBannerPage();
});

function initializeBannerPage() {
  var modal = $("#editBannerModal");
  var editButtons = $(".edit-banner");
  var span = $(".close");

  editButtons.click(function() {
    var bannerId = $(this).data("id");
    $.ajax({
      url: '/admin/actions/get_banner.php',
      method: 'GET',
      data: { id: bannerId },
      dataType: 'json',
      success: function(data) {
        populateModalForm(data);
        modal.show();
      },
      error: function(xhr, status, error) {
        console.error("Error fetching banner data:", error);
        showToast("Error fetching banner data", "error");
      }
    });
  });

  span.click(function() {
    modal.hide();
  });

  $(window).click(function(event) {
    if (event.target == modal[0]) {
      modal.hide();
    }
  });

  handleFormSubmit("#editBannerForm", '/admin/pages/banners.php', function(response) {
    if (response.success) {
      showToast("Banner updated successfully", "success");
      updateBannerUI(response);
      $("#editBannerModal").hide();
    } else {
      showToast("Error: " + (response.error || "Cannot update banner"), "error");
    }
  });
}

function populateModalForm(data) {
  $("#banner_id").val(data.id);
  $("#banner_type").val(data.banner_type);
  $("#current_banner_img").val(data.banner_img);
  $("#title").val(data.title);
  $("#subtitle").val(data.subtitle);
  $("#release_date").val(data.release_date);
  $("#url").val(data.url);

  $(".main-banner-field").toggle(data.banner_type === 'main');
}

function updateBannerUI(data) {
  var bannerRow = $(`tr[data-id="${data.id}"]`);
  bannerRow.find(".banner-img").attr("src", "/public/images/banner/" + data.banner_img);
  bannerRow.find(".banner-url").text(data.url);

  if (data.banner_type === 'main') {
    bannerRow.find(".banner-title").text(data.title);
    bannerRow.find(".banner-subtitle").text(data.subtitle);
    bannerRow.find(".banner-release-date").text(data.release_date);
  }
}