//  Import CSS.
import './style.scss';
import './editor.scss';
import axios from 'axios';

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
const { MediaUpload } = wp.editor;

const el = wp.element.createElement;
const iconEl = el('svg', { width: 128, height: 128, viewBox: "0 0 128 128" },
    el('rect', { x: 0, y: 0, width: 128, height: 128, stroke: "white" }),
    el('path', { d: "M41.7607 39.0615H52.8432V60.866L73.2637 39.0615H86.6547L66.1434 60.2237L87.5885 88.9388H74.2753L58.66 67.706L52.8432 73.6982V88.9388H41.7607V39.0615Z", fill: "white" })
);

registerBlockType( 'klarity/klarity-vimeo-wrapper', {
	title: __( 'Vimeo wrapper' ),
	icon: iconEl,
	category: 'common',
	keywords: [
		__( 'Vimeo wrapper block' )
	],
  attributes: {
    link: {
      type: 'string',
      default: 'https://player.vimeo.com/video/'
    },
    videoThumbnail: {
      type: 'string',
      default: ''
    },
    videoDuration: {
      type: 'string',
      default: '00:00'
    },
    isThumbnailFullWidth: {
      type: 'boolean',
      default: false
    }
  },

  edit: props => {

    let {attributes: {link, videoThumbnail, videoDuration, isThumbnailFullWidth}, setAttributes} = props;

    const setLink = event => {
      const selected = event.target;
      setAttributes({link: selected.value});
      event.preventDefault();
    };

    const setVideoDuration = event => {
      const selected = event.target;
			setAttributes({videoDuration: selected.value});
      event.preventDefault();
		};

    const setVideoThumbnail = imageObject => {
			const backgroundImage = imageObject.url;
			setAttributes({videoThumbnail: backgroundImage});
		};

    const setIsThumbnailFullWidth = event => {
      const selected = event.target;
      setAttributes({isThumbnailFullWidth: selected.checked});
    };

    const getVideoThumbnail = event => {
      event.preventDefault();
      setAttributes({videoThumbnail: ""});
      let videoId = link.split('/').slice(-1).pop();

      axios.get(`https://vimeo.com/api/v2/video/${videoId}.json`)
      .then(res => {
        const thumbnail_large = res.data[0].thumbnail_large;
        // removing the size restriction:
        // e.g https://i.vimeocdn.com/video/748767326_100x75.webp -> https://i.vimeocdn.com/video/748767326.webp
        const thumbnail_max_size = thumbnail_large.replace(/_.*\./, ".");
        setAttributes({videoThumbnail: thumbnail_max_size});
      });
    };

    return (
      <form id="header_video_edit">
        <div className="form-group">
          <label>Vimeo Link:
            <input type="text" value={link} onChange={setLink}/>
          </label>
          <button onClick={getVideoThumbnail}>Automatically Try to Fetch Video Thumbnail</button>
        </div>
        <div className="form-group">
          <MediaUpload
              onSelect={setVideoThumbnail}
              type="image"
              value={videoThumbnail}
              render={({ open }) => (
                  <button onClick={open}>
                    Manually Select Thumbnail
                  </button>
              )}
            />
            <p><strong>Thumbnail url:</strong> {videoThumbnail || 'No background image selected'}</p>
        </div>
        <div className="form-group">
          <label>Video duration:
            <input type="text" value={videoDuration} onChange={setVideoDuration}/>
          </label>
        </div>
        <div className="form-group">
          <label>
            <input type="checkbox" checked={isThumbnailFullWidth} onClick={setIsThumbnailFullWidth}/>
            Full width
          </label>
        </div>
      </form>
    );
  },

  save: () => {
    return null
  },
});
