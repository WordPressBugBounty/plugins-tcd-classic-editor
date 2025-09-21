jQuery(function ($) {
  // カラーピッカー
  initVanillaPicker($, $(".js-tcdce-color-picker"));

  // 画像アップローダー
  initImageUploader($);

  // 画面遷移無しのform送信
  noReloadformSubmit();

  // プリセット
  initQtPreset($);

  // リピーター
  initQtRepeater($);

  // プレビュー
  initQtPreview($);

  // プレビューのデバイス切り替え
  $(document).on("click", ".js-tcdce-preview-switch", function () {
    $(this).parent().attr("data-device", $(this).attr("data-type"));
  });
  $(document).on("click", ".js-tcdce-preview-guide", function () {
    if ($(this).parent().attr("data-guide") == "on") {
      $(this).parent().attr("data-guide", "off");
    } else {
      $(this).parent().attr("data-guide", "on");
    }
  });

  // その他
  $(document).on("click", ".js-tcdce-reset", function () {
    $(this).toggleClass("is-open");
  });

  // text validation
  $(document).on("input", ".js-tcdce-empty-validation", function () {
    if ($(this).val()) {
      $(this).removeClass("is-empty");
    } else {
      $(this).addClass("is-empty");
    }
  });

  // リセットボタンのEnter対策としてjsで送信
  $(document).on("click", "#js-tcdce-reset-button", function () {
    $(this).attr("type", "submit");
  });

  // インポート/エクスポートの表示切り替え
  $(document).on("click", function (e) {
    if (
      !$(e.target).closest(".js-tcdce-setting-data-form").length &&
      !$(e.target)
        .closest(".tcdce-setting-data-action")
        .find(".js-tcdce-setting-data-form-input:checked").length
    ) {
      $(".js-tcdce-setting-data-form-input:checked")
        .removeAttr("checked")
        .prop("checked", false)
        .change();
    }
  });

  // インポート実行後のメッセージ
  const url = new URL(window.location.href);
  if (url.searchParams.get("imported")) {
    // パラメータ削除
    url.searchParams.delete("imported");
    window.history.replaceState({}, "", url.toString());
    // メッセージ表示切り替え
    const page = document.getElementById("js-tcdce-page");
    page?.classList.add("is-imported");
    setTimeout(() => {
      page?.classList.remove("is-imported");
    }, 5000);
  }
});

// color picker
function initVanillaPicker($, colorPickers) {
  if (!colorPickers.length) {
    return;
  }

  colorPickers.each(function () {
    var colorPickerValue = $(this).find("input");
    var picker = new Picker({
      parent: $(this)[0],
      popup: "top",
      alpha: false,
      color: colorPickerValue.attr("value"),
      editor: true,
      cancelButton: false,
      onChange: function (color) {
        var hexColor = color.hex.substring(0, 7);
        colorPickerValue.parent().css({
          color: hexColor,
          background: hexColor,
        });
        colorPickerValue.attr("value", hexColor);
        colorPickerValue.trigger("change");
      },
    });

    colorPickerValue.on("colorChange", function () {
      $(this)
        .parent()
        .css({
          color: $(this).attr("value"),
          background: $(this).attr("value"),
        });
      picker.setColor($(this).attr("value"), true);
    });
  });
}

// image uploader
function initImageUploader($) {
  var custom_uploader = {};

  $(document).on("click", ".js-tcdce-image", function (e) {
    var closest = $(this).closest(".js-tcdce-image-closest");
    var targetInput = closest.find(".js-tcdce-image-id");
    var targetId = targetInput.attr("name");
    var targetPreview = closest.find(".js-tcdce-image-preview");

    if (custom_uploader[targetId]) {
      custom_uploader[targetId].open();
      return;
    }

    custom_uploader[targetId] = wp.media({
      title: TCDCE_OBJECT.media_text,
      library: {
        type: "image",
      },
      button: {
        text: TCDCE_OBJECT.media_text,
      },
      multiple: false,
    });

    // 選択した画像をセット
    custom_uploader[targetId].on("open", function () {
      var selection = custom_uploader[targetId].state().get("selection");
      if (targetInput.attr("value")) {
        selection.add(wp.media.attachment(targetInput.attr("value")));
      }
    });

    // 画像選択時のイベント
    custom_uploader[targetId].on("select", function () {
      var imageObj = custom_uploader[targetId]
        .state()
        .get("selection")
        .first()
        .toJSON();
      var imageId = imageObj.id;
      var imageUrl = imageObj.sizes.full.url;
      targetInput.attr("value", imageId);
      targetPreview.css("background-image", "url(" + imageUrl + ")");
      targetInput
        .next()
        .attr("value", "url(" + imageUrl + ")")
        .trigger("change");
    });

    custom_uploader[targetId].open();
  });

  // 削除
  $(document).on("click", ".js-tcdce-image-delete", function (e) {
    var closest = $(this).closest(".js-tcdce-image-closest");
    closest.find(".js-tcdce-image-preview").removeAttr("style");
    closest.find(".js-tcdce-image-id").attr("value", "");
    closest
      .find(".js-tcdce-preview-option")
      .attr("value", "")
      .trigger("change");
  });
}

// ページ遷移無しでフォーム送信
function noReloadformSubmit() {
  var targetWrapper = document.getElementById("js-tcdce-page");
  var targetForm = document.getElementById("js-tcdce-form");
  if (targetForm) {
    // reset
    if (targetWrapper.classList.contains("is-reset")) {
      setTimeout(() => {
        targetWrapper.classList.remove("is-reset");
      }, 5000);
    }

    document.addEventListener("click", (e) => {
      if (e.target && e.target.classList.contains("js-tcdce-form-submit")) {
        var thisButton = e.target;
        var formData = new FormData(targetForm);
        var formAction = targetForm.getAttribute("action");
        var formoptions = {
          method: "POST",
          body: formData,
        };

        thisButton.classList.add("is-click");

        fetch(formAction, formoptions).then((e) => {
          // success
          if (e.status === 200) {
            thisButton.classList.remove("is-click");
            targetWrapper.classList.add("is-success");
            setTimeout(() => {
              targetWrapper.classList.remove("is-success");
            }, 5000);
            return;
          }

          // failed
          thisButton.classList.remove("is-click");
          targetWrapper.classList.add("is-failed");
          setTimeout(() => {
            targetWrapper.classList.remove("is-failed");
          }, 5000);
        });
      }
    });
  }
}

// プリセット関連
function initQtPreset($) {
  // preset modal open
  $(document).on("click", ".js-tcdce-preset-open", function () {
    var scrollTop = $(window).scrollTop();
    var adminbarHeight = $("#wpadminbar").innerHeight();
    var currentPreset = $(this)
      .find(".js-tcdce-preset-target-value")
      .attr("value");
    var presetType = $(this).attr("data-preset-type");
    var thisModal = $(
      '.js-tcdce-preset-modal[data-preset-type="' + presetType + '"]'
    );
    var thisModalScroll = thisModal.find(".tcdce-modal__scroll");
    var selectedPreset = thisModal.find(
      '.js-tcdce-preset-item[data-preset="' + currentPreset + '"]'
    );

    if (thisModal.length) {
      // body fixed
      $("body").addClass("is-modal-open");

      // scroll
      thisModalScroll.scrollTop(selectedPreset.position().top - 100);

      // display
      thisModal.addClass("is-open");
      thisModal.css({
        top: scrollTop + "px",
        height: $(window).height() - adminbarHeight + "px",
      });

      // プリセットの対象を指定
      $(this).closest(".js-tcdce-preview-closest").addClass("is-target");
      selectedPreset.addClass("is-select");
    }
  });

  // preset modal close
  $(document).on("click", ".js-tcdce-preset-close", function () {
    $("body").removeClass("is-modal-open");
    $(".js-tcdce-preset-modal").removeClass("is-open");
    $(".js-tcdce-preset-item.is-select").removeClass("is-select");
    $(".js-tcdce-preset-load").removeClass("is-selected");
    $(".js-tcdce-preview-closest").removeClass("is-target");
  });

  // preset select
  $(document).on("click", ".js-tcdce-preset-item", function () {
    $(this)
      .parent()
      .find(".js-tcdce-preset-item.is-select")
      .removeClass("is-select");
    $(this).addClass("is-select");
    $(this)
      .closest(".js-tcdce-preset-modal")
      .find(".js-tcdce-preset-load")
      .addClass("is-selected");
  });

  // preset load
  $(document).on("click", ".js-tcdce-preset-load", function () {
    var presetTarget = $(".js-tcdce-preview-closest.is-target");
    var closest = $(this).closest(".js-tcdce-preset-modal");
    var selectItem = closest.find(".js-tcdce-preset-item.is-select");
    if (selectItem.length) {
      // presetに反映
      presetTarget
        .find(".js-tcdce-preset-target-value")
        .attr("value", selectItem.attr("data-preset"));
      presetTarget
        .find(".js-tcdce-preset-target-label")
        .attr("value", selectItem.attr("data-preset-label"));

      // text反映
      var textTarget = selectItem
        .find(".js-tcdce-preview-option--text-target")
        .text();
      presetTarget
        .find(".js-tcdce-preview-target--text")
        .attr("value", textTarget);

      // style反映
      presetStyle = selectItem.attr("style");
      presetTarget.find(".js-tcdce-preview-target").attr("style", presetStyle);

      // style取得
      var target = selectItem[0];
      var styleObject = {};
      for (let i = 0; i < target.style.length; i++) {
        var propertyName = target.style[i];
        var propertyValue = target.style.getPropertyValue(propertyName);
        styleObject[propertyName] = propertyValue;
      }

      // ループ
      $.each(styleObject, function (property, value) {
        var targetInput = presetTarget.find(
          'input[name*="\\[' + property + '\\]"]'
        );

        // number
        if (targetInput.attr("type") == "number") {
          targetInput.val(parseInt(value)).trigger("change");

          // radio
        } else if (targetInput.attr("type") == "radio") {
          presetTarget
            .find("input[name*=" + property + '][value="' + value + '"]')
            .prop("checked", true)
            .trigger("change");

          // hidden
        } else if (targetInput.attr("type") == "hidden") {
          targetInput.attr("value", value);

          // color
        } else if (targetInput.hasClass("js-tcdce-color-picker--value")) {
          setTimeout(() => {
            targetInput
              .attr("value", value)
              .trigger("change")
              .trigger("colorChange");
          }, 10);

          // text
        } else if (targetInput.attr("type") == "text") {
          targetInput.attr("value", value);
        }

        // select
        var targetSelect = presetTarget.find(
          'select[name*="\\[' + property + '\\]"]'
        );
        if (targetSelect.length) {
          targetSelect.val(value).trigger("change");
        }
      });
    }

    // 閉じる
    closest.find(".js-tcdce-preset-close").trigger("click");
  });
}

// リピーター
function initQtRepeater($) {
  // repater opening and closing
  $(document).on("click", ".js-tcdce-repeater-title", function () {
    $(this).parent().toggleClass("is-open");
  });

  // reflect title repater
  $(document).on("change", ".js-tcdce-repeater-label", function () {
    $(this)
      .closest(".js-tcdce-repeater-item")
      .find(".js-tcdce-repeater-title-label")
      .text($(this).val());
  });

  // repater sortable
  $(".js-tcdce-repeater-sortable").sortable({
    axis: "y",
    cursor: "grabbing",
    handle: ".js-tcdce-repeater-sortable-handle",
    placeholder: "tcdce-repeater__placeholder",
  });

  // repater delete
  $(document).on("click", ".js-tcdce-repeater-delete", function () {
    var target = $(this).closest(".js-tcdce-repeater-item");
    if (confirm($(this).data("msg"))) {
      target.fadeOut(300, function () {
        target.remove();
      });
    }
    return false;
  });

  // repeater add (ajax)
  $(document).on("click", ".js-tcdce-repeater-add", function () {
    var itemType = $(this).attr("data-item");
    var repeaterItems = $(this).closest("form").find(".js-tcdce-repeater-item");
    var registerKeys = [];

    if (repeaterItems.length) {
      repeaterItems.each(function () {
        registerKeys.push($(this).attr("data-key"));
      });
    }

    $.ajax({
      type: "POST",
      url: TCDCE_OBJECT.ajax_url,
      data: {
        action: "tcdce_repeater_add",
        nonce: TCDCE_OBJECT.ajax_nonce,
        item: itemType,
        register_keys: registerKeys,
      },
    })
      .done(function (data) {
        // nonce error
        if (data == "nonce_error") {
          alert(TCDCE_OBJECT.ajax_error_message);
        }

        var $data = $(data).addClass("is-open");
        if ($data.length) {
          // アイテム追加
          $(".js-tcdce-repeater-list").append($data);

          // マージンリセット
          $data
            .find('input[name*=margin][type="radio"]:checked')
            .trigger("change");

          // カラーピッカー初期化
          setTimeout(() => {
            initVanillaPicker($, $data.find(".js-tcdce-color-picker"));
          }, 10);
        }
      })
      .fail(function (jqXHR, textStatus, errorThrow) {
        alert(TCDCE_OBJECT.ajax_error_message);
      });
  });
}

// プレビュー
function initQtPreview($) {
  // preview
  $(document).on(
    "change",
    ".js-tcdce-preview-option, .js-tcdce-preview-option--radio",
    function () {
      // heading
      var closest = $(this).closest(".js-tcdce-preview-closest");
      var target = $(this)
        .closest(".js-tcdce-preview-closest")
        .find(".js-tcdce-preview-target");

      if (closest.length) {
        closest
          .find(
            ".js-tcdce-preview-option, .js-tcdce-preview-option--radio:checked"
          )
          .each(function () {
            var property = $(this).attr("data-property");
            var value = $(this).val();

            // text
            if ($(this).hasClass("js-tcdce-preview-target--text")) {
              target.find(".js-tcdce-preview-option--text-target").text(value);
            }

            // プロパティがなければスキップ
            if (!property) {
              return true;
            }

            // number（px）つける
            if ($(this).attr("type") == "number") {
              value = value + "px";
            }
            target.css(property, value);
          });
      }

      // radio
      if (
        $(this).prop("tagName") == "INPUT" &&
        $(this).attr("type") == "radio"
      ) {
        if ($(this).prop("checked")) {
          var currentIndex = $(this).parent().index();
          $(this)
            .closest(".tcdce-edit__options-field__item")
            .attr("data-checked", currentIndex);
        }
      }

      // select
      if ($(this).prop("tagName") == "SELECT") {
        var currentIndex = $(this).find("option:selected").index();
        $(this)
          .closest(".tcdce-edit__options-field__item")
          .attr("data-select", currentIndex);
      }
    }
  );

  // load
  $(
    ".js-tcdce-preview-option, .js-tcdce-preview-option--radio:checked"
  ).trigger("change");
}
