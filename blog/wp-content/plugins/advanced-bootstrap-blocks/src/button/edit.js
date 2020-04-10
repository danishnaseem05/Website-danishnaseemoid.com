const { __ } = wp.i18n;

const {
  Fragment
} = wp.element; 

const {
  SelectControl,
  RadioControl,
  BaseControl,
  PanelBody,
  PanelRow,
  FormToggle,
} = wp.components;

const {
  InspectorControls,
  URLInputButton,
} = wp.blockEditor;

import {
  buttonStyle,
  getCaretPosition,
  setCaretPosition
} from './utils'; 

export const edit = (props) => {
  const {
    attributes: {
      anchor,
      text,
      link,
      style,
      outline,
      block,
      size,
      newWindow,
    },
    className,
    setAttributes
  } = props;
  

  const onChangeStyle = ( value ) => {
    setAttributes( { style: value } );
  }

  const onChangeOutline = () => {
    setAttributes( { outline: !outline } );
  }

  const onChangeBlock = () => {
    setAttributes( { block: !block } );
  }

  const onChangeSize = ( value ) => {
    setAttributes( { size: value } );
  }
  
  const onChangeNewWindow = () => {
    setAttributes( { newWindow: !newWindow } );
  }

  let onInputTimer; 

  const onInput = (e) => {
    const target = e.target;
    const position = getCaretPosition(target);
    const newText = target.text; 
    clearTimeout(onInputTimer); 
    if (text != newText) {
      setAttributes( { text: newText } );
      onInputTimer = setTimeout(function() { 
        setCaretPosition(target, position);
      }, 1);
    }
  }

  return (
    <Fragment>
      <div className={[block ? "d-flex" : "d-inline-flex", "align-items-start"].join(" ")}>
        <a
          {...anchor ? { id: anchor } : { } }
          className={[className, size, buttonStyle(props.attributes), "btn"].join(" ")} 
          href={link} 
          target={newWindow && '_blank'}
          role="button"
          rel={newWindow && 'noopener noreferrer'}
          contentEditable
          onInput={onInput}
          onClick={(e) => e.preventDefault()}
          style={{ marginTop: '3px' }}
        >
          {text}
        </a>
        <URLInputButton
          label="Link picker"
          url={ link }
          onChange={ ( link ) => setAttributes( { link } ) }
        />
      </div>
      <InspectorControls>
          <PanelBody
              title={ __( 'Button Settings', 'advanced-bootstrap-blocks' ) }
          >
            <PanelRow>
              <BaseControl
                className="w-100"
              >
                <SelectControl
                  label="Button Style"
                  value={ style }
                  options={ [
                      { label: 'Primary', value: 'btn-primary' },
                      { label: 'Secondary', value: 'btn-secondary' },
                      { label: 'Success', value: 'btn-success' },
                      { label: 'Danger', value: 'btn-danger' },
                      { label: 'Warning', value: 'btn-warning' },
                      { label: 'Info', value: 'btn-info' },
                      { label: 'Light', value: 'btn-light' },
                      { label: 'Dark', value: 'btn-dark' },
                      { label: 'Link', value: 'btn-link' },
                  ] }
                  onChange={onChangeStyle}
                />
              </BaseControl>
            </PanelRow>
            <PanelRow>
                <label
                    htmlFor="form-toggle-outline"
                >
                    { __( 'Button Outline Setting', 'advanced-bootstrap-blocks' ) }
                </label>
                <FormToggle
                    id="form-toggle-outline"
                    label={ __( 'Toggle Outline', 'advanced-bootstrap-blocks' ) }
                    checked={outline}
                    onChange={onChangeOutline}
                />
            </PanelRow>
            <PanelRow>

                <label
                    htmlFor="form-toggle-block"
                >
                    { __( 'Button Block Setting', 'advanced-bootstrap-blocks' ) }
                </label>
                <FormToggle
                    id="form-toggle-block"
                    label={ __( 'Toggle block', 'advanced-bootstrap-blocks' ) }
                    checked={block}
                    onChange={onChangeBlock}
                />
            </PanelRow>
            <PanelRow>
              <BaseControl
                className="w-100"
              >
                <RadioControl
                    label="Button size"
                    help=""
                    selected={ size }
                    options={ [
                      { label: 'Default', value: '' },
                      { label: 'Large', value: 'btn-lg' },
                      { label: 'Small', value: 'btn-sm' },
                    ] }
                    onChange={onChangeSize}
                  />
                </BaseControl>
            </PanelRow>
            <PanelRow>
                <label
                    htmlFor="form-toggle-window"
                >
                    { __( 'Open link in new window', 'advanced-bootstrap-blocks' ) }
                </label>
                <FormToggle
                    id="form-toggle-window"
                    label={ __( 'Open link in new window', 'advanced-bootstrap-blocks' ) }
                    checked={newWindow}
                    onChange={onChangeNewWindow}
                />
            </PanelRow>
          </PanelBody>
      </InspectorControls>
    </Fragment>
  );
}