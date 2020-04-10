const { 
  createHigherOrderComponent 
} = wp.compose;

export const modifyBlockListBlockCard = createHigherOrderComponent( ( BlockListBlock ) => {
  return ( props ) => {
    if (props.block.name == "advanced-bootstrap-blocks/card") {
      props.className = [props.block.attributes.className, "card"].join(" ");
    }
    return <BlockListBlock { ...props } />;
  };
}, 'modifyBlockListBlockCard' );

export const modifyGetSaveElementCard = (element, blockType, attributes ) => {
  if (!element) {
    return;
  }
  if (blockType.name == 'advanced-bootstrap-blocks/card') {
    return (
      <div className={ [element.props.className, "card"].join(" ") }>
        {element}
      </div>
    )
  }
  return element;
}