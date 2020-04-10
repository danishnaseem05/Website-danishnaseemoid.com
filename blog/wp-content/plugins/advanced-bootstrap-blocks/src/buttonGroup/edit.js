const { __ } = wp.i18n;

const {
  InnerBlocks,
  InspectorControls
} = wp.blockEditor;

const {
  Autocomplete,
  FocalPointPicker,
  SelectControl,
  BaseControl,
  PanelBody,
  PanelRow,
  FormToggle,
} = wp.components;

const {
  Fragment
} = wp.element; 

export const edit = (props) => {
  const {
    attributes: {
      ariaLabel,
      TEMPLATE
    },
    className,
    setAttributes
  } = props;
  return (
    <Fragment>
      <InnerBlocks template={ TEMPLATE } />
      <InspectorControls>
          <PanelBody
              title={ __( 'Button Group Settings', 'advanced-bootstrap-blocks' ) }
              initialOpen={true}
          >
            <PanelRow className="mt-0">
              <BaseControl
                className="d-block"
              >
                <label 
                  className="d-block font-weight-bold mw-100"
                  htmlFor="form-aria-label">
                    { __( 'ARIA Label (for screen-readers)', 'advanced-bootstrap-blocks' ) }
                  </label>
                <input 
                  id="form-aria-label"
                  className="d-block mw-100"
                  type="text"
                  value={ariaLabel}
                  onChange={(e) => setAttributes({ ariaLabel: e.target.value })}
                />
              </BaseControl>
            </PanelRow>
          </PanelBody>
      </InspectorControls> 
    </Fragment>
  );
}