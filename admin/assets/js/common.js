// Xử lý AJAX và hiển thị thông báo
function handleAjax(url, method, data, successCallback, errorCallback) {
  $.ajax({
    url: url,
    method: method,
    data: data,
    processData: false,
    contentType: false,
    dataType: 'json',
    success: function(response) {
      if (response.success) {
        if (response.message) {
          showToast(response.message, "success");
        }
        if (successCallback) successCallback(response);
      } else {
        showToast("Error: " + (response.error || "Unknown error"), "error");
        if (errorCallback) errorCallback(response);
      }
    },
    
    error: function(xhr, status, error) {
      console.error("AJAX error:", error);
      showToast("An error occurred. Please try again.", "error");
      if (errorCallback) errorCallback(error);
    }
  });
}

// Xử lý form submission
function handleFormSubmit(formId, url, successCallback, errorCallback) {
  $(formId).on("submit", function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    handleAjax(url, 'POST', formData, successCallback, errorCallback);
  });
}

// Xử lý preview hình ảnh
function handleImagePreview(inputId, previewId) {
  $(inputId).change(function() {
    var input = this;
    var preview = $(previewId);
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        preview.html('<img src="' + e.target.result + '" alt="Preview" style="max-width: 200px;">');
      };
      reader.readAsDataURL(input.files[0]);
    }
  });
}

// Hiển thị thông báo
function showToast(message, type = 'success') {
  const toast = $('<div>').addClass('toast').addClass(`toast-${type}`).text(message);
  $('body').append(toast);
  setTimeout(() => {
    toast.addClass('show');
    setTimeout(() => {
      toast.removeClass('show');
      setTimeout(() => {
        toast.remove();
      }, 300);
    }, 8000);
  }, 100);
}