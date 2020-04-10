const {
  Fragment
} = wp.element; 

const {
  InnerBlocks
} = wp.blockEditor;

export const save = (props) => {
  return (
    <Fragment>
      <InnerBlocks.Content />
    </Fragment>
  );
}