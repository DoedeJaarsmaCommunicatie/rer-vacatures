import React, { Component } from 'react';
import { Title, UnderlinedSecondary } from '../../../style/main.css'

export default class Header extends Component {
    render() {
        return (
            <>
                <Title>
                    Sollicitate van <UnderlinedSecondary>{this.props.solicitor.firstname} {this.props.solicitor.lastname}</UnderlinedSecondary>
                </Title>
            </>
        )
    }
}
