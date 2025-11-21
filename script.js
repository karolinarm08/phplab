(() => {
  const PAGE_ID = location.origin + location.pathname;
  const EDIT_FORM_ID = "content-editor";
  const clientStartTime = performance.now();

  const canBeEdited = (element) => {
    if (!element || element.nodeType !== 1) return false;
    if (element.closest(`#${EDIT_FORM_ID}`)) return false;
    if (element.closest(".block2") && !element.closest(".blockX")) return false;
    const tagName = element.tagName.toLowerCase();
    if (["script", "style", "link", "meta", "head", "title", "html", "body"].includes(tagName))
      return false;
    const rect = element.getBoundingClientRect();
    return rect.width > 0 && rect.height > 0;
  };

  const generateStorageKey = (element) => {
    const pathParts = [];
    let node = element;
    while (node && node.nodeType === 1 && node !== document.documentElement) {
      const tag = node.tagName.toLowerCase();
      const siblings = Array.from(node.parentNode.children).filter(
        (sibling) => sibling.tagName === node.tagName
      );
      const index = siblings.indexOf(node);
      pathParts.unshift(`${tag}:nth(${index})`);
      node = node.parentElement;
    }
    return pathParts.join(" > ");
  };

  const applyStoredData = (element, storedData) => {
    if (!storedData) return;
    if (storedData.type === "image") {
      if (element.tagName === "IMG") {
        element.src = storedData.src;
        element.alt = storedData.alt ?? "";
      } else {
        element.innerHTML = "";
        const imgElement = document.createElement("img");
        imgElement.src = storedData.src;
        imgElement.alt = storedData.alt || "";
        imgElement.style =
          "max-width:100%;max-height:250px;display:block;object-fit:contain;";
        element.appendChild(imgElement);
      }
    } else {
      element.textContent = storedData.text ?? "";
    }
  };

  const createEditForm = (() => {
    const formElement = document.createElement("form");
    formElement.id = EDIT_FORM_ID;
    formElement.innerHTML = `
      <div data-text>
        <textarea rows="3" style="width:100%;box-sizing:border-box;font-size:14px;padding:6px;"></textarea>
      </div>
      <div data-img style="display:none">
        <input type="url" placeholder="https://example.com/photo.jpg" style="width:100%;box-sizing:border-box;padding:6px;font-size:14px;">
        <input type="text" placeholder="alt-текст (необов’язково)" style="width:100%;margin-top:4px;box-sizing:border-box;padding:6px;font-size:14px;">
      </div>
      <div style="margin-bottom:6px;font-size:14px">
        <label><input type="radio" name="editMode" value="text" checked> Текст</label>
        <label style="margin-left:10px"><input type="radio" name="editMode" value="image"> Фото (URL)</label>
      </div>
      <div style="margin-top:10px;display:flex;justify-content:flex-end;gap:8px;">
        <button type="button" data-action="cancel" style="padding:6px 12px;border:1px solid #ccc;background:#f5f5f5;border-radius:4px;cursor:pointer;">Скасувати</button>
        <button type="submit" style="padding:6px 12px;border:1px solid #ccc;background:#007bff;color:white;border-radius:4px;cursor:pointer;">Зберегти</button>
      </div>
    `;
    Object.assign(formElement.style, {
      position: "fixed",
      zIndex: 9999,
      background: "#fff",
      border: "1px solid #ccc",
      borderRadius: "4px",
      padding: "10px",
      display: "none",
      fontFamily: "sans-serif",
      fontSize: "14px",
      width: "320px",
      boxSizing: "border-box",
    });

    formElement.addEventListener("change", (event) => {
      if (event.target.name === "editMode") {
        const mode = event.target.value;
        formElement.querySelector("[data-text]").style.display =
          mode === "text" ? "block" : "none";
        formElement.querySelector("[data-img]").style.display =
          mode === "image" ? "block" : "none";
      }
    });

    document.body.appendChild(formElement);
    return formElement;
  })();

  let activeElement = null;
  const showEditorForm = (element) => {
    const margin = 8;
    const rect = element.getBoundingClientRect();

    createEditForm.style.display = "block";
    createEditForm.style.visibility = "hidden";
    createEditForm.style.left = "0px";
    createEditForm.style.top = "0px";

    const formWidth = createEditForm.offsetWidth;
    const formHeight = createEditForm.offsetHeight;

    let left = window.scrollX + rect.left + margin;
    let top = window.scrollY + rect.bottom + margin;

    if (top + formHeight > window.scrollY + window.innerHeight - margin)
      top = window.scrollY + rect.top - formHeight - margin;
    if (left + formWidth > window.scrollX + window.innerWidth - margin)
      left = window.scrollX + window.innerWidth - formWidth - margin;

    createEditForm.style.left = `${left}px`;
    createEditForm.style.top = `${top}px`;
    createEditForm.style.visibility = "visible";
  };
  const hideEditorForm = () => (createEditForm.style.display = "none");

  const applyInitialData = () => {
    const startTime = performance.now();
    let restoredCount = 0;
    if (!window.__savedContent) return { count: 0, ms: 0 };
    
    document.querySelectorAll("body *").forEach((element) => {
      if (!canBeEdited(element)) return;
      const key = generateStorageKey(element);
      const rawData = window.__savedContent[key];
      if (!rawData) return;
      try {
        applyStoredData(element, JSON.parse(rawData));
        restoredCount++;
      } catch(e) { console.error("Error parsing stored data for", element, e); }
    });
    return { count: restoredCount, ms: performance.now() - startTime };
  };
  
  const showPerformanceInfo = ({ serverTime, clientTime, dbTime, renderTime }) => {
    const panel = document.createElement("div");
    Object.assign(panel.style, {
      position: "fixed", top: "0", left: "0", width: "100%", background: "linear-gradient(90deg, #0d1117, #161b22)", color: "#c9d1d9", fontFamily: "monospace", fontSize: "13px", display: "flex", justifyContent: "center", alignItems: "center", padding: "6px 10px", gap: "30px", borderBottom: "1px solid #30363d", zIndex: 9999, boxShadow: "0 2px 8px rgba(0,0,0,0.4)",
    });

    const makeItem = (label, value, color = "#8b949e") => {
      const span = document.createElement("span");
      span.innerHTML = `<b style="color:${color}">${label}</b> ${value}`;
      return span;
    };

    const fmt = (t) => (t != null ? `${t.toFixed(1)} ms` : "—");
    
    panel.append(
      makeItem("Server", fmt(serverTime), "#58a6ff"),
      makeItem("Database", fmt(dbTime), "#a371f7"),
      makeItem("Client Render", fmt(renderTime), "#f2cc60"),
      makeItem("Total Client", fmt(clientTime), "#3fb950")
    );
    document.body.appendChild(panel);
  };
  
  document.addEventListener("DOMContentLoaded", () => {
    const { ms: renderTime } = applyInitialData();
    const clientTime = performance.now() - clientStartTime;
    const serverTime = typeof window.__serverGenMs === "number" ? window.__serverGenMs : null;
    const dbTime = typeof window.__dbTimeMs === "number" ? window.__dbTimeMs : null;

    showPerformanceInfo({ serverTime, clientTime, dbTime, renderTime });

    document.body.addEventListener("click", (event) => {
        if (event.target.closest(`#${EDIT_FORM_ID}`)) return;
        const clickedElement = event.target;
        if (!canBeEdited(clickedElement)) return;

        event.preventDefault();
        event.stopPropagation();
        activeElement = clickedElement;

        const isImage = clickedElement.tagName === "IMG" || clickedElement.querySelector("img");
        createEditForm.querySelector(`[value="${isImage ? "image" : "text"}"]`).checked = true;
        createEditForm.querySelector("[data-text]").style.display = isImage ? "none" : "block";
        createEditForm.querySelector("[data-img]").style.display = isImage ? "block" : "none";
        
        createEditForm.querySelector("textarea").value = clickedElement.textContent.trim();
        const img = clickedElement.tagName === "IMG" ? clickedElement : clickedElement.querySelector("img");
        createEditForm.querySelector('[data-img] input[type="url"]').value = isImage ? img?.src || "" : "";
        createEditForm.querySelector('[data-img] input[type="text"]').value = isImage ? img?.alt || "" : "";

        showEditorForm(clickedElement);
    }, true);

    createEditForm.addEventListener("click", (event) => {
      if (event.target.dataset.action === "cancel") {
        hideEditorForm();
        activeElement = null;
      }
    });
    
    createEditForm.addEventListener("submit", (event) => {
      event.preventDefault();
      if (!activeElement) return;

      const submitButton = createEditForm.querySelector('button[type="submit"]');
      submitButton.disabled = true;
      submitButton.textContent = "Збереження...";

      const mode = createEditForm.querySelector('input[name="editMode"]:checked').value;
      let dataToStore;
      let elementToSave = activeElement;

      if (mode === "image") {
        const srcValue = createEditForm.querySelector('[data-img] input[type="url"]').value.trim();
        const altValue = createEditForm.querySelector('[data-img] input[type="text"]').value.trim();
        if (!srcValue) {
          alert("Введіть URL зображення!");
          submitButton.disabled = false;
          submitButton.textContent = "Зберегти";
          return;
        }
        dataToStore = { type: "image", src: srcValue, alt: altValue };
        applyStoredData(activeElement, dataToStore);
      } else {
        const textValue = createEditForm.querySelector("textarea").value;
        if (activeElement.tagName === "IMG") {
            elementToSave = activeElement.parentElement || activeElement;
        }
        dataToStore = { type: "text", text: textValue };
        applyStoredData(elementToSave, dataToStore);
      }

      const payload = {
        page_id: PAGE_ID,
        element_key: generateStorageKey(elementToSave),
        content_data: JSON.stringify(dataToStore)
      };

      fetch('api.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
      })
      .then(response => response.json())
      .then(data => {
        if (!data.success) {
          console.error('Server Error:', data.message);
          alert('Не вдалося зберегти дані. Помилка сервера.');
        }
      })
      .catch(error => {
        console.error('Network Error:', error);
        alert('Не вдалося зберегти дані. Перевірте з\'єднання.');
      })
      .finally(() => {
        submitButton.disabled = false;
        submitButton.textContent = "Зберегти";
        hideEditorForm();
        activeElement = null;
      });
    });

    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape") hideEditorForm();
    });
  });
})();