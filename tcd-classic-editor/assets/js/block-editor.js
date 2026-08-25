/**
 * デフォルトエディタの調整
 * ブロックバリエーションの登録
 * ツールバーにマーカー登録
 */
(function (wp) {

  // 既存ブロック関連の削除
  wp.domReady(function () {

    const { unregisterBlockType } = wp.blocks;
    const { unregisterBlockVariation } = wp.blocks;
    const { unregisterBlockStyle } = wp.blocks;

    // ブロックの削除
    const tcdceUnregisterBlockTypes = [
      // テキスト
      'core/preformatted',
      'core/pullquote',
      'core/verse',
      // メディア
      'core/media-text',
      // デザイン
      // 'core/group',
      'core/more',
      // ウィジェット
      'core/archives',
      'core/calendar',
      'core/categories',
      'core/latest-comments',
      'core/latest-posts',
      'core/page-list',
      'core/rss',
      'core/search',
      'core/social-links',
      'core/tag-cloud',
      // テーマ
      'core/navigation',
      'core/site-logo',
      'core/site-title',
      'core/site-tagline',
      'core/query',
      'core/avatar',
      'core/post-title',
      'core/post-excerpt',
      'core/post-featured-image',
      'core/post-author',
      'core/post-author-name',
      'core/post-date',
      'core/post-terms',
      'core/post-navigation-link',
      'core/read-more',
      'core/comments',
      'core/post-comments-form',
      'core/loginout',
      'core/term-description',
      'core/query-title',
      'core/post-author-biography',
      'core/template-part',
      'core/post-content',
      'core/query-pagination',
      'core/comment-author-name',
      'core/comment-content',
      'core/comment-date',
      'core/comment-edit-link',
      'core/comment-reply-link',
      'core/comments-title',
    ];
    tcdceUnregisterBlockTypes.forEach(blockType => {
      unregisterBlockType(blockType);
    });

    // ブロックバリエーション削除
    const allowedEmbedVariation = [
      'twitter',
      'youtube',
    ];
    wp.blocks.getBlockVariations('core/embed').forEach(block => {
      if (!allowedEmbedVariation.includes(block.name)) {
        unregisterBlockVariation('core/embed', block.name);
      }
    });
    // グループのバリエーション
    unregisterBlockVariation('core/group', 'group-row');
    unregisterBlockVariation('core/group', 'group-stack');

    // ブロックスタイルの削除
    const tcdceUnregisterBlockStyles = [
      // 引用のスタイル
      { block: 'core/quote', style: 'plain' },
      { block: 'core/quote', style: 'default' },
      // 画像のスタイル
      { block: 'core/image', style: 'rounded' },
      // ボタンのスタイル
      { block: 'core/button', style: 'fill' },
      { block: 'core/button', style: 'outline' }
    ];
    tcdceUnregisterBlockStyles.forEach(({ block, style }) => {
      unregisterBlockStyle(block, style);
    });

  });

  // 段落ブロックのみマーカーを使用可能にする
  const conditionalMarker = wp.compose.compose(
    wp.data.withSelect(function (select) {
      return {
        selectedBlock: select('core/block-editor').getSelectedBlock()
      }
    }),
    wp.compose.ifCondition(function (props) {
      return (
        props.selectedBlock &&
        props.selectedBlock.name === 'core/paragraph'
      );
    })
  );

  // マーカーをツールバーに登録
  const tcdceRegisterMarker = ({ name, title, className }) => wp.richText.registerFormatType(
    `tcdce/${name}`,
    {
      title: title,
      tagName: 'span',
      className: className,
      edit: conditionalMarker(function (props) {
        return wp.element.createElement(
          wp.blockEditor.RichTextToolbarButton,
          {
            icon: 'tcdce-marker',
            title: title,
            onClick: function () {
              props.onChange(
                wp.richText.toggleFormat(props.value, {
                  type: `tcdce/${name}`,
                  attributes: {
                    class: 'tcdce-marker',
                  },
                })
              );
            }
          }
        );
      }),
    }
  );

  // ブロックバリエーション/ツールバーの登録
  if (tcdceBlockEditorObj) {
    tcdceBlockEditorObj.forEach(({ name, settings }) => {
      // ツールバー
      if (name == 'toolbar/marker') {
        tcdceRegisterMarker(settings);
      } else if (settings.attributes.level) {
        settings.attributes.level = Number(settings.attributes.level);
        wp.blocks.registerBlockVariation(name, settings);
      } else {
        wp.blocks.registerBlockVariation(name, settings);
      }
    });
  }

})(window.wp);
