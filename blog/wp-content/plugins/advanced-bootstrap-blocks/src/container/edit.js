const { __ } = wp.i18n;

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

const {
  MediaUpload,
  InspectorControls,
  InnerBlocks
} = wp.blockEditor;

export const edit = (props) => {
  const {
    attributes: {
      anchor,
      className,
      isFluid,
      isWrapped,
      backgroundImage,
      backgroundRepeat,
      backgroundSize,
      backgroundPosition,
      backgroundAttachment,
      TEMPLATE,
    },
    setAttributes
  } = props;

  const onChangeToggleFluid = () => {
    setAttributes( { isFluid: !isFluid } );
  }

  const onChangeToggleWrapped = () => {
    setAttributes( { isWrapped: !isWrapped } );
  }

  const onSelectBackgroundImage = (value ) => {
    setAttributes({
      backgroundImage: value.sizes,
    });
  }

  const classNameAttribute = () => {
    const containerClass = isFluid ? "container-fluid" : "container"; 
    return [className, containerClass].join(" ");
  }
  if (typeof anchor != "undefined") console.log(anchor);
  return (
    <Fragment>
      <Fragment>
        <div 
          // style={{outline: '1px dashed red'}}
          {...anchor ? { id: anchor } : { } }
          className={classNameAttribute()}
          { // conditionally render style attribute with backgroundImage property
            ...backgroundImage.hasOwnProperty("full") ? {
              style: {
                backgroundImage: `url(${backgroundImage.full.url})`,
                ...backgroundSize ? { backgroundSize: `${backgroundSize}` } : { },
                ...backgroundRepeat ? { backgroundRepeat: `${backgroundRepeat}` } : { },
                ...backgroundPosition ? backgroundPosition.hasOwnProperty("x") ? { backgroundPosition: `${ Math.round(backgroundPosition.x * 100) }% ${ Math.round(backgroundPosition.y * 100) }%` } : { } : { },
                ...backgroundAttachment ? { backgroundAttachment: `${backgroundAttachment}` } : { },
              }
            } : {

            }
          }
        >
          <InnerBlocks 
            template={ TEMPLATE }
            allowedBlocks={['advanced-bootstrap-blocks/row']}
          />
        </div>
      </Fragment>
      <InspectorControls>
          <PanelBody
              title={ __( 'Container Settings', 'advanced-bootstrap-blocks' ) }
              initialOpen={true}
          >
            <PanelRow>
                <label
                    htmlFor="form-toggle-fluid"
                >
                    { __( 'Full-width Container', 'advanced-bootstrap-blocks' ) }
                </label>
                <FormToggle
                    id="form-toggle-fluid"
                    label={ __( 'Full-width container', 'advanced-bootstrap-blocks' ) }
                    checked={ isFluid }
                    onClick={ onChangeToggleFluid }
                />
            </PanelRow>
            <PanelRow>
                <label
                    htmlFor="form-toggle-fluid"
                >
                    { __( 'Wrap container', 'advanced-bootstrap-blocks' ) }
                </label>
                <FormToggle
                    id="form-toggle-fluid"
                    label={ __( 'Add Wrapper', 'advanced-bootstrap-blocks' ) }
                    checked={ isWrapped }
                    onClick={ onChangeToggleWrapped }
                />
            </PanelRow>
          </PanelBody>
          <PanelBody
            title={ __( 'Background Image Settings', 'advanced-bootstrap-blocks' ) }
            initialOpen={false}
          >
            <PanelRow>
              <div className="w-100">
                <label
                    htmlFor="form-media-select"
                    style={{
                      display: 'block'
                    }}
                >
                    { __( 'Background Image', 'advanced-bootstrap-blocks' ) }
                </label>
                <MediaUpload 
                    id="form-media-select"
                    onSelect={onSelectBackgroundImage}
                    render={ ({open}) => {
                        return backgroundImage.hasOwnProperty("medium") && (
                          <div>
                            <img 
                              src={backgroundImage.medium.url} 
                              alt="Background image preview"
                              className="w-100 mb-2 rounded-sm"
                            />
                            <div className="btn-group">
                              <button className="btn btn-primary btn-sm" onClick={open}>
                                Replace
                              </button>
                              <button className="btn btn-dark btn-sm" onClick={() => { setAttributes({backgroundImage: false})}}>
                                Clear
                              </button>
                            </div>
                          </div>
                        ) || (
                          <button className="btn btn-dark btn-sm" onClick={open}>
                            Select
                          </button>
                        );
                    }}
                  />  
              </div>
            </PanelRow>
            <PanelRow>
                <SelectControl
                    label="Background Size"
                    value={ backgroundSize }
                    className="d-block w-100 mb-2"
                    options={ [
                        { label: '', value: '' },
                        { label: 'Cover', value: 'cover' },
                        { label: 'Contain', value: 'contain' },
                        { label: 'Custom', 
                          value: typeof backgroundSize !== "undefined" && 
                                 backgroundSize !== 'cover' && backgroundSize !== 'contain' &&
                                 backgroundSize || '100% auto'  
                        },
                    ] }
                    onChange={ ( backgroundSize ) => { setAttributes( { backgroundSize } ) } }
                />
            </PanelRow>
            {
              typeof backgroundSize !== "undefined" && backgroundSize.length > 0 && 
              backgroundSize !== "cover" && backgroundSize !== "contain" && 
                <PanelRow className="mt-0">
                  <BaseControl
                    className="d-block w-100"
                  >
                    <input 
                      type="text"
                      value={backgroundSize}
                      onChange={(e) => setAttributes({ backgroundSize: e.target.value })}
                    />
                  </BaseControl>
                </PanelRow>
            }
            <PanelRow className="mt-0">
              <SelectControl
                  label="Background Repeat"
                  value={ backgroundRepeat }
                  className="d-block w-100 mb-2"
                  options={ [
                      { label: '', value: '' },
                      { label: 'repeat-x', value: 'repeat-x' },
                      { label: 'repeat-y', value: 'repeat-y' },
                      { label: 'repeat', value: 'repeat' },
                      { label: 'space', value: 'space' },
                      { label: 'round', value: 'round' },
                      { label: 'no-repeat', value: 'no-repeat' },
                      { label: 'repeat space', value: 'repeat space' },
                      { label: 'repeat repeat', value: 'repeat repeat' },
                      { label: 'round space', value: 'round space' },
                      { label: 'no-repeat round', value: 'no-repeat round' },
                  ] }
                  onChange={ ( backgroundRepeat ) => { setAttributes( { backgroundRepeat } ) } }
              />
            </PanelRow>
            <PanelRow className="mt-0">
                <SelectControl
                    label="Background Position"
                    value={ JSON.stringify(backgroundPosition) }
                    className="d-block w-100 mb-2"
                    options={ [
                        { label: '', value: '{}' },
                        { 
                          label: 'Focal point', 
                          value: backgroundPosition.hasOwnProperty("x") ? 
                                 JSON.stringify({ x: backgroundPosition.x, y: backgroundPosition.y }) : 
                                 JSON.stringify({ x: 0.5, y: 0.5 })
                        },
                        { 
                          label: 'Custom', 
                          value: backgroundPosition.hasOwnProperty("z") ? 
                                 JSON.stringify(backgroundPosition) : 
                                 JSON.stringify({ x: backgroundPosition.x || 0.5, y: backgroundPosition.y || 0.5, z: 1 })
                        },
                    ] }
                    onChange={( backgroundPosition ) => { 
                      setAttributes( { backgroundPosition: JSON.parse(backgroundPosition) } )
                    }}
                />
            </PanelRow>
            
            {
              ( backgroundImage.hasOwnProperty("medium") && backgroundPosition.hasOwnProperty("x") && !backgroundPosition.hasOwnProperty("z") ) &&  
              <PanelRow className="mt-0">
                <FocalPointPicker 
                    url={ backgroundImage.medium.url }
                    dimensions={{ width: backgroundImage.medium.width, height: backgroundImage.medium.height }}
                    value={ backgroundPosition }
                    onChange={ ( backgroundPosition ) => { setAttributes( { backgroundPosition } ) } } 
                />
              </PanelRow>
            }
            {
              ( backgroundPosition.hasOwnProperty("x") && backgroundPosition.hasOwnProperty("z") ) &&  
              <Fragment>
                <PanelRow className="my-0">
                  <BaseControl
                      className="d-block w-100"
                    >
                    <label
                        htmlFor="backgroundPositionX"
                        style={{
                          display: 'block'
                        }}
                    >
                        { __( 'Background Position X', 'advanced-bootstrap-blocks' ) }
                    </label>
                    <input 
                      id="backgroundPositionX"
                      type="number"
                      value={backgroundPosition.hasOwnProperty("x") ? Math.round(backgroundPosition.x * 100) : '' }
                      onChange={(e) => setAttributes({ backgroundPosition: { 
                        x: e.target.value / 100, 
                        y: backgroundPosition.y, 
                        ...backgroundPosition.hasOwnProperty("z") ? { z: 1 } : { },
                      }})}
                    />
                  </BaseControl>
                </PanelRow>
                <PanelRow className="my-0">
                  <BaseControl
                    className="d-block w-100"
                  >
                    <label
                        htmlFor="backgroundPositionY"
                        style={{
                          display: 'block'
                        }}
                    >
                        { __( 'Background Position Y', 'advanced-bootstrap-blocks' ) }
                    </label>
                    <input 
                      id="backgroundPositionY"
                      type="number"
                      value={backgroundPosition.hasOwnProperty("y") ? Math.round(backgroundPosition.y * 100) : '' }
                      onChange={(e) => setAttributes({ backgroundPosition: { 
                        y: e.target.value / 100, 
                        x: backgroundPosition.x,
                        ...backgroundPosition.hasOwnProperty("z") ? { z: 1 } : { },
                      }})}
                    />
                  </BaseControl>
                </PanelRow>
              </Fragment>
            }
            <PanelRow
              className="mt-0"
            >
                <SelectControl
                    label="Background Attachment"
                    value={ backgroundAttachment }
                    className="d-block w-100 mb-2"
                    options={ [
                        { label: '', value: '' },
                        { label: 'scroll', value: 'scroll' },
                        { label: 'fixed', value: 'fixed' },
                        { label: 'local', value: 'local' },
                        { label: 'initial', value: 'initial' },
                        { label: 'inherit', value: 'inherit' },
                    ] }
                    onChange={ ( backgroundAttachment ) => { setAttributes( { backgroundAttachment } ) } }
                />
            </PanelRow>
          </PanelBody>
      </InspectorControls> 
    </Fragment>
  );
}