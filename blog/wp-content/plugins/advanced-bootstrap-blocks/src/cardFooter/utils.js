const { 
  createHigherOrderComponent 
} = wp.compose;

export const modifyBlockListBlockCardFooter = createHigherOrderComponent( ( BlockListBlock ) => {
  return ( props ) => {
    if (props.block.name == 'advanced-bootstrap-blocks/card-footer') {
      props.className = [props.block.attributes.className, "card-footer"].join(" ");
    }
    return <BlockListBlock { ...props } />;
  };
}, 'modifyBlockListBlockCardFooter' );

export const modifyGetSaveElementCardFooter = (element, blockType, attributes ) => {
  if (!element) {
    return;
  }
  if (blockType.name == 'advanced-bootstrap-blocks/card-footer') {
    return (
      <div className={ [element.props.className, "card-footer"].join(" ") }>
        {element}
      </div>
    )
  }
  return element;
}