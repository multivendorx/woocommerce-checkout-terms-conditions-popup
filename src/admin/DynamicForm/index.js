/* global termsconditionsappLocalizer */
import React from 'react';
import Select from 'react-select';
import axios from 'axios';
import Slider from '@mui/material/Slider';

export default class DynamicForm extends React.Component {
	state = {};
	constructor(props) {
		super(props);
		this.state = {
			datamclist: [],
			from_loading: false,
			errordisplay: '',
			hover_on: false
		};
		this.handleOMouseEnter = this.handleOMouseEnter.bind( this );
		this.handleOMouseLeave = this.handleOMouseLeave.bind( this );
	}

	handleOMouseEnter( e ) {
		this.setState( {
			hover_on: true,
		} );
	}

	handleOMouseLeave( e ) {
		this.setState( {
			hover_on: false,
		} );
	}

	onSubmit = (e) => {
		// block to refresh pages
		const prop_submitbutton =
			this.props.submitbutton && this.props.submitbutton === 'false'
				? ''
				: 'true';
		if (prop_submitbutton) {
			e.preventDefault();
		}
		this.setState({ from_loading: true });
		axios({
			method: this.props.method,
			url: termsconditionsappLocalizer.apiUrl + '/' + this.props.url,
			data: {
				model: this.state,
				modulename: this.props.modulename,
			},
		}).then((res) => {
			this.setState({
				from_loading: false,
				errordisplay: res.data.error,
			});
			setTimeout(() => {
				this.setState({ errordisplay: '' });
			}, 2000);
			if (res.data.redirect_link) {
				window.location.href = res.data.redirect_link;
			}
		});
	};

	componentDidMount() {
		//Fetch all datas
		this.props.model.map((m) => {
			this.setState({
				[m.key]: m.database_value,
			});
		});
	}

	onChange = (e, key, type = 'single', from_type = '', array_values = []) => {
		if (type === 'single') {
			if (from_type === 'select') {
				this.setState(
					{
						[key]: array_values[e.index],
					},
					() => {}
				);
			} else if (from_type === 'multi-select') {
				this.setState(
					{
						[key]: e,
					},
					() => {}
				);
			} else {
				this.setState(
					{
						[key]: e.target.value,
					},
					() => {}
				);
			}
		} else {
			// Array of values (e.g. checkbox): TODO: Optimization needed.
			const found = this.state[key]
				? this.state[key].find((d) => d === e.target.value)
				: false;

			if (found) {
				const data = this.state[key].filter((d) => {
					return d !== found;
				});
				this.setState({
					[key]: data,
				});
			} else {
				const others = this.state[key] ? [...this.state[key]] : [];
				this.setState({
					[key]: [e.target.value, ...others],
				});
			}
		}
		if (this.props.submitbutton && this.props.submitbutton === 'false') {
			if (key != 'password') {
				setTimeout(() => {
					this.onSubmit('');
				}, 10);
			}
		}
	};

	renderForm = () => {
		const model = this.props.model;
		const formUI = model.map((m, index) => {
			
			const key = m.key;
			const type = m.type || 'text';
			const props = m.props || {};
			const name = m.name;
			let value = m.value;
			const placeholder = m.placeholder;
			const limit = m.limit;
			let input = '';

			const target = key;
			
			value = this.state[target] || '';
			
			if (
				m.restricted_page &&
				m.restricted_page === this.props.location
			) {
				return false;
			}

			// If no array key found
			if (!m.key) {
				return false;
			}

			// for checkbox selection
			if (
				m.depend_checkbox &&
				this.state[m.depend_checkbox] &&
				this.state[m.depend_checkbox].length === 0
			) {
				return false;
			}

			// for checkbox selection
			if (
				m.not_depend_checkbox &&
				this.state[m.not_depend_checkbox] &&
				this.state[m.not_depend_checkbox].length > 0
			) {
				return false;
			}

			if (m.depend && !this.state[m.depend]) {
				return false;
			}

			if (type === 'text' || 'url' || 'password' || 'email' || 'number') {
				input = (
					<div className="woo-settings-basic-input-class">
						<input
							{...props}
							className="woo-setting-form-input"
							type={type}
							key={key}
							id={m.id}
							placeholder={placeholder}
							name={name}
							value={value}
							onChange={(e) => {
								this.onChange(e, target);
							}}
						/>
						{m.desc ? (
							<p
								className="woo-settings-metabox-description"
								dangerouslySetInnerHTML={{ __html: m.desc }}
							></p>
						) : (
							''
						)}
					</div>
				);
			}
			
			if (type === 'color') {
				value = this.state[target] || '#000000';
				input = (
					<div className="woo-settings-color-picker-parent-class">
						<input
							{...props}
							className="woo-setting-color-picker"
							type={type}
							key={key}
							id={m.id}
							name={name}
							value={value}
							onChange={(e) => {
								this.onChange(e, target);
							}}
						/>
						{m.desc ? (
							<p
								className="woo-settings-metabox-description"
								dangerouslySetInnerHTML={{ __html: m.desc }}
							></p>
						) : (
							''
						)}
					</div>
				);
			}

			if (type === 'blocktext') {
				input = (
					<div className="woo-blocktext-class">
						{m.blocktext ? (
							<p
								className="woo-settings-metabox-description-code"
								dangerouslySetInnerHTML={{
									__html: m.blocktext,
								}}
							></p>
						) : (
							''
						)}
					</div>
				);
			}

			if (type === 'textarea') {
				input = (
					<div className="woo-setting-from-textarea">
						<textarea
							{...props}
							className={m.class ? m.class : 'woo-form-input'}
							key={key}
							maxLength={limit}
							placeholder={placeholder}
							name={name}
							value={value}
							rows="4"
							cols="50"
							onChange={(e) => {
								this.onChange(e, target);
							}}
						/>
						{m.desc ? (
							<p
								className="woo-settings-metabox-description"
								dangerouslySetInnerHTML={{ __html: m.desc }}
							></p>
						) : (
							''
						)}
					</div>
				);
			}

			if (type === 'select') {
				const options_data = [];
				const defaultselect = [];
				input = m.options.map((o, index) => {
					if (o.selected) {
						defaultselect[index] = {
							value: o.value,
							label: o.label,
							index,
						};
					}
					options_data[index] = {
						value: o.value,
						label: o.label,
						index,
					};
				});
				input = (
					<div className="woo-form-select-field-wrapper">
						<Select
							className={key}
							value={value ? value : ''}
							options={options_data}
							onChange={(e) => {
								this.onChange(
									e,
									m.key,
									'single',
									type,
									options_data
								);
							}}
						></Select>
						{m.desc ? (
							<p
								className="woo-settings-metabox-description"
								dangerouslySetInnerHTML={{ __html: m.desc }}
							></p>
						) : (
							''
						)}
					</div>
				);
			}

			if (type === 'checkbox') {
				input = (
					<div
						className={
							m.right_content
								? 'woo-checkbox-list-side-by-side'
								: m.parent_class
								? 'woo-checkbox-list-side-by-side'
								: ''
						}
					>
						{m.options.map((o) => {
							let checked = false;
							if (value && value.length > 0) {
								checked =
									value.indexOf(o.value) > -1 ? true : false;
							}
							return (
								<div
									className={
										m.right_content
											? 'woo-toggle-checkbox-header'
											: m.parent_class
											? m.parent_class
											: ''
									}
								>
									<React.Fragment key={'cfr' + o.key}>
										{m.right_content ? (
											<p
												className="woo-settings-metabox-description"
												dangerouslySetInnerHTML={{
													__html: o.label,
												}}
											></p>
										) : (
											''
										)}
										<div className="woo-toggle-checkbox-content">
											<input
												{...props}
												className={m.class}
												type={type}
												id={`woo-toggle-switch-${o.key}`}
												key={o.key}
												name={o.name}
												checked={checked}
												value={o.value}
												onChange={(e) => {
													this.onChange(
														e,
														m.key,
														'multiple'
													);
												}}
											/>
											<label
												htmlFor={`woo-toggle-switch-${o.key}`}
											></label>
										</div>
										{m.right_content ? (
											''
										) : (
											<p
												className="woo-settings-metabox-description"
												dangerouslySetInnerHTML={{
													__html: o.label,
												}}
											></p>
										)}
										{o.hints ? (
											<span className="dashicons dashicons-info">
												<div className="woo-hover-tooltip">
													{o.hints}
												</div>
											</span>
										) : (
											''
										)}
									</React.Fragment>
								</div>
							);
						})}
						{m.desc ? (
							<p
								className="woo-settings-metabox-description"
								dangerouslySetInnerHTML={{ __html: m.desc }}
							></p>
						) : (
							''
						)}
					</div>
				);
			}

			if ( type === 'slider' ) {
				var slider_val = parseInt(value);
				input = (
					<div className="woo-settings-color-picker-parent-class">
						<Slider
							aria-label="Default"
							valueLabelDisplay="auto"
							value = { slider_val }
							onChange={ ( e ) => {
								this.onChange(
									e,
									target
								);
							} }
						/>
						{ m.desc ? (
							<p
								className="woo-settings-metabox-description"
								dangerouslySetInnerHTML={ { __html: m.desc } }
							></p>
						) : (
							''
						) }
					</div>
				);
				
			}

			if ( type === 'example_button' ) {
				input = (
					<div className="woo-settings-example-button-class">
						{ <div
							onMouseEnter={ this.handleOMouseEnter }
							onMouseLeave={ this.handleOMouseLeave }
							style={ {
								color:
									this.state.hover_on
										? this.state['button_text_color_hover']
										: this.state['button_text_color'],
								fontSize:
									this.state['button_font_size'],
								padding:
									this.state['button_padding'],
								borderRadius:
									this.state['button_border_radius'],
								border: `${ this.state['button_border_size'] }px solid ${ this.state['button_border_color'] }`,
								
								background: 
									this.state.hover_on 
										? this.state['button_background_color_hover']
										: this.state['button_background_color'],
								verticalAlign: 'middle',
								textDecoration: 'none',
								width: this.state['button_width'],
								height: this.state['button_height'],
							} }
						>
							{termsconditionsappLocalizer.custom_button_name}
						</div>
						}
					</div>
				);
				
			}

			if (type === 'section') {
				input = (
					<div className="woo-setting-section-divider">&nbsp;</div>
				);
			}

			return m.type === 'section' || m.label === 'no_label' ? (
				input
			) : (
				<div key={'g' + key} className="woo-form-group">
					<label
						className="woo-settings-form-label"
						key={'l' + key}
						htmlFor={key}
					>
						<p dangerouslySetInnerHTML={{ __html: m.label }}></p>
					</label>
					<div className="woo-settings-input-content">{input}</div>
				</div>
			);
		});
		return formUI;
	};


	render() {
		const prop_submitbutton =
			this.props.submitbutton && this.props.submitbutton === 'false'
				? ''
				: 'true';
		return (
			<div className="woo-dynamic-fields-wrapper">
				{this.state.errordisplay ? (
					<div className="woo-notic-display-title">
						<i className="success-icon icon-checkmark"></i>
						{this.state.errordisplay}
					</div>
				) : (
					''
				)}

				<form
					className="woo-dynamic-form"
					onSubmit={(e) => {
						this.onSubmit(e);
					}}
				>
					{this.renderForm()}

				</form>
			</div>
		);
	}
}
