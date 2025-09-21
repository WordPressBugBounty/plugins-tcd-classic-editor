document.addEventListener("DOMContentLoaded", (event) => {
  // 本文のマージン対策
  const entryContents = document.querySelectorAll(".tcdce-body");
  if (entryContents) {
    entryContents.forEach((el) => {
      // 条件を判定するための関数
      const isTextOnlyParagraph = (el) => {
        // pタグじゃなければ終了
        if (!el || el.tagName?.toLowerCase() !== "p") return false;

        // 囲み枠の場合は終了
        if (el.classList.contains("tcdce-box")) {
          return false;
        }

        // ボタンを含む場合は終了
        if (el.querySelector(".tcdce-button")) {
          return false;
        }

        // 画像を含む場合は終了
        if (el.querySelector("img")) {
          return false;
        }

        // テキストが空白だけなら除外
        const text = el.textContent.trim();
        if (!text) {
          // テキストが完全に空ならfalse
          return false;
        }

        return true;
      };

      // 最初の要素
      const firstEl = el.firstElementChild;
      if (isTextOnlyParagraph(firstEl)) {
        firstEl.style.marginTop = "calc((1em - 1lh) / 2)";
      }

      // 最後の要素
      const lastEl = el.lastElementChild;
      if (isTextOnlyParagraph(lastEl)) {
        lastEl.style.marginBottom = "calc((1em - 1lh) / 2)";
      }
    });
  }

  // marker
  var markers = document.querySelectorAll(".tcdce-marker");
  if (markers.length) {
    markers.forEach((el) => {
      el.classList.add("is-hide");

      // borderColor上書き
      var markerColor = el.style.getPropertyValue("border-bottom-color");
      if (markerColor) {
        el.setAttribute("style", "--tcdce-marker-color:" + markerColor + ";");
      }

      const options = {
        root: null,
        rootMargin: "0px",
        threshold: 0.5,
      };

      new IntersectionObserver(function (entries) {
        entries.forEach((entry) => {
          if (
            entry.isIntersecting &&
            !entry.target.classList.contains("is-active")
          ) {
            entry.target.classList.remove("is-hide");
          }
        });
      }, options).observe(el);
    });
  }

  // toc
  var toc = document.querySelector(".tcdce-body .p-toc");
  var tocOpen = document.getElementById("js-tcdce-toc-open");
  var tocClose = document.querySelectorAll(".js-tcdce-toc-close");
  var tocModalLinks = document.querySelectorAll(
    '#js-tcdce-toc-modal a[href^="#"]'
  );
  if (tocOpen && tocClose.length) {
    // modal open
    tocOpen.addEventListener("click", (e) => {
      document
        .getElementById("js-tcdce-toc-modal")
        .classList.toggle("is-active");
    });

    // modal close
    tocClose.forEach((el) => {
      el.addEventListener("click", (e) => {
        tocOpen.click();
      });
    });

    // scroll icon
    window.addEventListener("scroll", (e) => {
      var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      var tocOffset =
        toc == null ? 1 : toc.getBoundingClientRect().top + scrollTop;
      if (tocOffset > scrollTop) {
        tocOpen.classList.remove("is-active");
      } else {
        tocOpen.classList.add("is-active");
      }
    });

    if (tocModalLinks.length) {
      for (let tocLink of tocModalLinks) {
        tocLink.addEventListener("click", (e) => {
          tocOpen.click();
        });
      }
    }
  }

  // tab content
  var tabs = document.querySelectorAll(".tcdce-tab__label-item");
  if (tabs.length) {
    for (let tab of tabs) {
      tab.addEventListener("click", (e) => {
        let children = e.target.parentNode.children;
        for (let child of children) {
          child.classList.remove("is-active");
        }
        e.target.classList.add("is-active");
      });
    }
  }
});
