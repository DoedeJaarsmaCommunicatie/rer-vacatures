import React, { Component } from 'react';
import Axios from 'axios';
import Header from './layouts/Header';
import Email from './views/Email';
import Columns from './layouts/Columns';
import Mobile from './views/Mobile';
import Actions from './views/Actions';
import Motivatie from './views/Motivatie';
import File from './views/File';

import { Column } from '../../style/main.css';
import Vacancy from './views/Vacancy'

export default class App extends Component {
    constructor(props) {
        super(props);

        const qp = new URLSearchParams(window.location.search);

        this.state = {
            searchQuery: window.location.search,
            s_id: qp.has('solicitor_id')? qp.get('solicitor_id') : 0,
            qp,
            solicitor: {
                post: {}
            }
        }
    }

    async componentDidMount() {
        this.setState({
            solicitor: (await Axios.post('/wp-admin/admin-post.php?action=get_vacature&solicitor_id=' + this.state.s_id)).data
        })
    }

    render() {
        return (
            <>
                <Header solicitor={this.state.solicitor} />
                <Columns>
                    <Column>
                    <Email solicitor={this.state.solicitor} />
                    <Mobile solicitor={this.state.solicitor} />
                    <Motivatie solicitor={this.state.solicitor} />
                    </Column>
                    <Column>
                        <Actions solicitor={this.state.solicitor} />
                        <Vacancy post={this.state.solicitor.post} />
                        <File file={this.state.solicitor.file} />
                    </Column>
                </Columns>
            </>
        )
    }
}
