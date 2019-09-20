import React, { Component } from 'react';
import { Card } from '../../../style/main.css'

export default class Vacancy extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <Card>
                <div className="title">
                    Voor vacature
                </div>

                <div className="content">
                    <a href={'/?p=' + this.props.post.ID}>
                        {this.props.post.post_title}
                    </a>
                </div>
            </Card>
        )
    }
}
