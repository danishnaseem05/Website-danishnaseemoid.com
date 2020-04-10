export const buttonStyle = ({ style, outline, block }) => {
  let styles = style; 
  if (outline)
    styles = styles.replace("btn-", "btn-outline-");
  if (block)
    styles = styles + " btn-block";
  return styles; 
}

// https://stackoverflow.com/questions/40491793/how-could-i-store-caret-position-in-an-editable-div
export const getCaretPosition = (element) => {
  let caretOffset = 0;
  let doc = element.ownerDocument || element.document;
  let win = doc.defaultView || doc.parentWindow;
  let sel;
  if (typeof win.getSelection != "undefined") {
    sel = win.getSelection();
    if (sel.rangeCount > 0) {
      let range = win.getSelection().getRangeAt(0);
      let preCaretRange = range.cloneRange();
      preCaretRange.selectNodeContents(element);
      preCaretRange.setEnd(range.endContainer, range.endOffset);
      caretOffset = preCaretRange.toString().length;
    }
  } else if ((sel = doc.selection) && sel.type != "Control") {
    let textRange = sel.createRange();
    let preCaretTextRange = doc.body.createTextRange();
    preCaretTextRange.moveToElementText(element);
    preCaretTextRange.setEndPoint("EndToEnd", textRange);
    caretOffset = preCaretTextRange.text.length;
  }
  return caretOffset;
}

export const setCaretPosition = (element, caretOffset) => {
  // element.focus();
  let textNode = element.firstChild || element;
  let range = document.createRange();
  range.setStart(textNode, caretOffset);
  range.setEnd(textNode, caretOffset);
  let sel = window.getSelection();
  sel.removeAllRanges();
  sel.addRange(range);
}
