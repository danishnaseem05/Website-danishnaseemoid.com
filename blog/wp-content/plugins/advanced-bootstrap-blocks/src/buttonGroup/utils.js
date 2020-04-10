const { 
  createHigherOrderComponent 
} = wp.compose;

export const modifyBlockListBlockButtonGroup = createHigherOrderComponent( ( BlockListBlock ) => {
  return ( props ) => {
    if (props.block.name == "advanced-bootstrap-blocks/button-group") {
      props.className = [props.block.attributes.className, "btn-group"].join(" ");
      props.ariaLabel = props.block.attributes.ariaLabel;
    }
    return <BlockListBlock { ...props } />;
  };
}, 'modifyBlockListBlockButtonGroup' );

export const modifyGetSaveElementButtonGroup = (element, blockType, attributes) => {
  if (!element) {
    return;
  }
  if (blockType.name == 'advanced-bootstrap-blocks/button-group') {
    return (
      <div 
        className={ [element.props.className, "btn-group"].join(" ") }
        role="group"
        aria-label={attributes.ariaLabel}>
        {element}
      </div>
    )
  }
  return element;
}