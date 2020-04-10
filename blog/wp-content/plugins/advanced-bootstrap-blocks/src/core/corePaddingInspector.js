const { __ } = wp.i18n;
const { Fragment, useEffect, useRef } = wp.element;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, PanelRow, RangeControl } = wp.components;
const { createHigherOrderComponent, withState } = wp.compose;

const strToRegex = (property, breakpoint) => {
  const paddingProperty = property.replace('a', '');
  const paddingBreakpoint = breakpoint.replace('xs', ''); 
  const regexString = `[p]{1}(property)[-](breakpoint)[-]?([0-5]\\b)`
    .replace('property', `${paddingProperty}`)
    .replace('breakpoint', `${paddingBreakpoint}`);

  return new RegExp(regexString); 
}

const removePaddingClass = (classNameList, property, breakpoint) => {
  if (typeof classNameList !== "undefined") {
    const regex = strToRegex(property, breakpoint); 

    return classNameList
      .split(" ")
      .filter(name => !name.match(regex)); 
  }
}

const returnPaddingValue = (props, property, breakpoint) => {
  if (typeof props.attributes.className !== "undefined") {
    const regex = strToRegex(property, breakpoint); 
    const results = props.attributes.className.length && props.attributes.className.match(regex) ? Number(props.attributes.className.match(regex)[3]) : -1; 
    if (results > -1) {
      return results;
    }
  }   
  return '';
}

const PaddingControl = withState({
  padding: -1,
} )( ({ padding, setState, property, breakpoint, defaultValue, classNameList, setAttributes } ) => {

  useEffect(() => {
    const classNameArray = removePaddingClass(classNameList, property, breakpoint) || [];
    let classNameListUpdated; 

    if (typeof padding !== "undefined" && padding.toString().length && padding > -1) {
      const newClassNamePaddingPrefix = `p${property}-${breakpoint}-`.replace('a','').replace('-xs', '');
      const newClassNamePaddingClass = padding >= 0 ? `${newClassNamePaddingPrefix}${padding}` : ''; 
      classNameListUpdated = typeof classNameArray !== "undefined" && classNameArray
        .concat(newClassNamePaddingClass)
        .join(' ')
        .trim()
        .replace(/\s\s+/, ' ');
    } else {
      classNameListUpdated = typeof classNameArray !== "undefined" && classNameArray
        .join(' ')
        .trim()
        .replace(/\s\s+/, ' ');
    }
    
    setAttributes( { 
      className: classNameListUpdated
    });  
  }, [padding]); 

  const getPaddingValue = (padding, defaultValue) => {
    return padding > -1 ? padding : defaultValue;
  }

  return (
    <RangeControl
      label={ 
        `.p${property}-${breakpoint}-${getPaddingValue(padding, defaultValue)}`
          .replace('a', '')
          .replace('-xs', '')
      }
      value={ getPaddingValue(padding, defaultValue) }
      allowReset
      onChange={ 
        padding => {
          setState({
            padding: padding
          });
        }
      }
      min={ 0 }
      max={ 5 }
      step={ 1 }
      marks={["0","1","2","3","4","5"]}
    />
  );
});

export const CustomPaddingInspector = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
    if (props.name.includes("advanced-bootstrap-blocks") || props.name.includes("core")) {
      const properties = ['a','x','y','t','r','b','l'];
      const breakpoints = ['xs','sm','md','lg','xl'];

      let paddingObject = paddingObject || {};
      breakpoints.map( breakpoint => {
        properties.map( property => { 
          const paddingValue = returnPaddingValue(props, property, breakpoint); 
          paddingObject[`p${property}-${breakpoint}`] = {
            ref: useRef(`p${property}-${breakpoint}`),
            property: property,
            breakpoint: breakpoint,
            defaultValue: typeof paddingValue !== "undefined" ? paddingValue : '',
          }
        });
      });  

      return (
        <Fragment>
          <BlockEdit { ...props } />
          <InspectorControls>
            <PanelBody
              title={ __( 'Block Padding', 'advanced-bootstrap-blocks' ) }
              initialOpen={false}
            >
            {
              props.isSelected && Object.keys(paddingObject).map((key, index) => {
                  return (
                    <PanelRow>
                      <PaddingControl 
                        property={ paddingObject[key].property } 
                        breakpoint={ paddingObject[key].breakpoint }
                        defaultValue={ paddingObject[key].defaultValue }
                        classNameList={ props.attributes.className }
                        setAttributes={ props.setAttributes }
                      />
                    </PanelRow>
                  );
              })
            }
            </PanelBody>
          </InspectorControls>
        </Fragment>
      );
    } else {
      return <BlockEdit { ...props } />; 
    }
	};
}, 'CustomPaddingInspector' );

